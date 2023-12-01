<?php

namespace App\Document\Sudoku;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="sudoku_db", collection="row", repositoryClass="App\Repository\UnitRepository")
 */
class Row extends Unit
{
    const NAME = "row";

    /**
     * @MongoDB\ReferenceOne(targetDocument="App\Document\Sudoku\Grid", inversedBy="rows", storeAs="id", orphanRemoval=true))
     * @MongoDB\Index
     */
    private $grid;

    /**
     * @MongoDB\ReferenceMany(targetDocument="App\Document\Sudoku\Cell", mappedBy="row", cascade="persist")
     */
    private $cells;


    public function __construct()
    {
        $this->cells = new ArrayCollection();
    }

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
        $cell->setRow($this);
        return $this->cells;
    }
}