<?php

namespace App\Manager;

use App\Document\Sudoku\Cell, App\Document\Sudoku\Column, App\Document\Sudoku\Grid, App\Document\Sudoku\Row, App\Document\Sudoku\Square;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SudokuManager
{
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * @TODO assertion sur le contenu des cellules (pas deux fois la même valeur dans une ligne par exemple)
     * @TODO appeler la validation sur la grille avant de la renvoyer
     *
     * @param UploadedFile $file
     * @return Grid
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function buildGrid(UploadedFile $file)
    {
        $grid = new Grid();
        $this->dm->persist($grid);

        $file = $file->openFile('r', false);

        $row_index = 1;
        $square_index = 1;

        // lecture du fichier ligne par ligne
        while ($file->current() != false) {
            $line = $file->current();
            // suppression des espaces si il y en a
            $line = str_replace(' ', '', rtrim($line));
            $arr = str_split($line);

            $column_index = 1;

            // On parcourt les valeurs sur la ligne
            foreach ($arr as $value) {
                $cell = new Cell();
                $cell->setValue($value);
                $cell->getValue() == 0 ? $cell->setEmpty(true) : null;

                $column = $this->addCellToUnit($grid, Column::NAME, $column_index, $cell);
                $this->dm->persist($column);

                $row = $this->addCellToUnit($grid, Row::NAME, $row_index, $cell);
                $this->dm->persist($row);

                $square = $this->addCellToUnit($grid, Square::NAME, $square_index, $cell);
                $this->dm->persist($square);

                if ($column_index%3 == 0 && $column_index < 9 || $column_index == 9 && $row_index%3 == 0) {
                    $square_index++;
                } else if ($column_index == 9 && $row_index%3 != 0) {
                    $square_index-=2;
                }

                $grid->addColumn($column);
                $grid->addRow($row);
                $grid->addSquare($square);

                $column_index++;
                $this->dm->flush();
            }
            $row_index++;
            $file->next();
        }

        $this->dm->refresh($grid);
        return $grid;
    }


    /**
     * @param Grid $grid
     * @param int $index
     * @return NULL|Column
     *
     * Retourne la colonne si elle existe, NULL sinon
     */
    public function getColumnByIndex(Grid $grid, int $index)
    {
        return $this->dm->getRepository(Column::class)
            ->findByGridAndPosition($grid, $index)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param Grid $grid
     * @param int $index
     * @return NULL|Row
     *
     * Retourne la ligne si elle existe, NULL sinon
     */
    public function getRowByIndex(Grid $grid, int $index)
    {
        return $this->dm->getRepository(Row::class)
            ->findByGridAndPosition($grid, $index)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param Grid $grid
     * @param int $index
     * @return NULL|Square
     *
     * Retourne le carré si il existe, NULL sinon
     */
    public function getSquareByIndex(Grid $grid, int $index)
    {
        return $this->dm->getRepository(Square::class)
            ->findByGridAndPosition($grid, $index)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param Grid $grid
     * @param string $unitName
     * @param int $unitIndex
     * @param Cell $cell
     * @return Column|Row|Square|NULL
     */
    private function addCellToUnit(Grid $grid, string $unitName, int $unitIndex, Cell $cell)
    {
        $unit = null;

        switch ($unitName) {
            case Column::NAME:
                $unit = $this->getColumnByIndex($grid, $unitIndex) ?: new Column();
                break;
            case Row::NAME:
                $unit = $this->getRowByIndex($grid, $unitIndex) ?: new Row();
                break;
            case Square::NAME:
                $unit = $this->getSquareByIndex($grid, $unitIndex) ?: new Square();
                break;
            default: throw new \InvalidArgumentException("Unit name is invalid");
        }

        $unit->setIndex($unitIndex);
        $unit->addCell($cell);
        $unit->setGrid($grid);

        return $unit;
    }

    public function getOneGrid()
    {
        return $this->dm->getRepository(Grid::class)
            ->findOneBy([]);
            //->find('5f54c04ac34200006000275c');
    }

    /**
     * @param Grid $grid
     */
    public function getGridSolution(Grid $grid)
    {
        if ($grid->isSolved()) {
            return $grid;
        }

        $emptyCells = $this->getEmptyCells($grid);
        $solutions = new ArrayCollection();

        /** @var Cell $emptyCell */
        foreach ($emptyCells as $emptyCell) {
            $solutions[$emptyCell->getId()] = new ArrayCollection([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        }

        $found = true;
        while (!$solutions->isEmpty() && $found) {

            $found = false;
            foreach ($emptyCells as $emptyCell) {
                if ($solutions->containsKey($emptyCell->getId())) {

                    $row = $emptyCell->getRow();
                    $column = $emptyCell->getColumn();
                    $square = $emptyCell->getSquare();

                    $cells = array_merge($row->getCells()->toArray(), $column->getCells()->toArray(), $square->getCells()->toArray());

                    /** @var Cell $cell */
                    foreach ($cells as $cell) {
                        $solutions[$emptyCell->getId()]->removeElement($cell->getValue());
                    }

                    if ($solutions[$emptyCell->getId()]->count() == 1) {
                        $found = true;
                        $emptyCell->setValue($solutions[$emptyCell->getId()]->first());
                        $this->dm->persist($emptyCell);
                        $solutions->remove($emptyCell->getId());
                    }
                }
            }
        }

        if (!$solutions->isEmpty()) {
            $iterator = $solutions->getIterator();
            $iterator->uasort(function ($a, $b) {
                return ($a->count() < $b->count()) ? -1 : 1;
            });

            $solutions = new ArrayCollection(iterator_to_array($iterator));
            $keys = $solutions->getKeys();

            $this->backtrack($grid, $solutions, $keys, 0);
        }

        $grid->setSolved(true);
        $this->dm->persist($grid);
        $this->dm->flush();

        return $grid;
    }

    private function backtrack(Grid $grid, ArrayCollection $solutions, array $cells, int $index)
    {
        $cellId = isset($cells[$index]) ? $cells[$index] : null;

        if (!$cellId) {
            return $grid;
        }

        foreach ($solutions->get($cellId) as $solution) {
            $cell = $this->dm->getRepository(Cell::class)->find($cellId);

            if ($this->isConsistent($cell, $solution)) {
                $cell->setValue($solution);

                if ($this->backtrack($grid, $solutions, $cells, array_search($cellId, $cells) + 1)) {
                    return true;
                }

                $cell->setValue(0);
            }
        }
        return false;
    }

    /**
     * Retourne vrai si la valeur peut être assigner à la cellule, faux sinon
     * @param Cell $emptyCell
     * @param int $value
     * @return bool
     */
    private function isConsistent(Cell $emptyCell, int $value)
    {
        $row = $emptyCell->getRow();
        $column = $emptyCell->getColumn();
        $square = $emptyCell->getSquare();

        $cells = new ArrayCollection(array_merge($row->getCells()->toArray(), $column->getCells()->toArray(), $square->getCells()->toArray()));

        /** @var Cell $cell */
        foreach ($cells as $cell) {
            if ($cell->getValue() == $value) {
                return false;
            }
        }
        return true;
    }

    /**
     * Renvoi les cases qui ne contient pas de valeurs, ici 0.
     * @param Grid $grid
     * @return mixed
     */
    public function getEmptyCells(Grid $grid)
    {
        return $this->dm->getRepository(Cell::class)
            ->findEmptyByGrid($grid)
            ->getQuery()
            ->execute();
    }
}