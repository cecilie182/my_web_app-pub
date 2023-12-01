<?php

namespace App\Document\Sudoku;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="sudoku_db", collection="column", repositoryClass="App\Repository\UnitRepository")
 */
class Column extends Unit
{
    const NAME = "column";

    /**
     * @MongoDB\ReferenceOne(targetDocument="App\Document\Sudoku\Grid", inversedBy="columns", storeAs="id", orphanRemoval=true))
     * @MongoDB\Index
     */
    private $grid;

    /**
     * @MongoDB\ReferenceMany(targetDocument="App\Document\Sudoku\Cell", mappedBy="column", cascade="persist")
     */
    private $cells;

    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return ArrayCollection
     */
    public function getCells()
    {
        return $this->cells;
    }

    /**
     * @param ArrayCollection $cells
     */
    public function setCells($cells): void
    {
        $this->cells = $cells;
    }

    public function addCell(Cell $cell)
    {
        $this->cells[] = $cell;
        $cell->setColumn($this);
        return $this->cells;
    }
}