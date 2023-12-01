<?php

namespace App\Document\Sudoku;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="sudoku_db", collection="grid")
 */
class Grid
{
    /**
     * @MongoDB\Id()
     */
    private $id;

    /**
     * @MongoDB\ReferenceMany(targetDocument="App\Document\Sudoku\Row", mappedBy="grid", cascade={"persist", "remove"})
     */
    private $rows;

    /**
     * @MongoDB\ReferenceMany(targetDocument="App\Document\Sudoku\Column", mappedBy="grid", cascade={"persist", "remove"})
     */
    private $columns;

    /**
     * @MongoDB\ReferenceMany(targetDocument="App\Document\Sudoku\Square", mappedBy="grid", cascade={"persist", "remove"})
     */
    private $squares;

    /**
     * @MongoDB\Field(type="boolean")
     */
    private $solved;


    public function __construct()
    {
        $this->rows = new ArrayCollection();
        $this->columns = new ArrayCollection();
        $this->squares = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param ArrayCollection $rows
     */
    public function setRows($rows): void
    {
        $this->rows = $rows;
    }

    public function addRow(Row $row)
    {
        $this->rows[] = $row;
        return $this->rows;
    }

    /**
     * @return ArrayCollection
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param ArrayCollection $columns
     */
    public function setColumns($columns): void
    {
        $this->columns = $columns;
    }

    public function addColumn(Column $column)
    {
        $this->columns[] = $column;
        return $this->columns;
    }

    /**
     * @return ArrayCollection
     */
    public function getSquares()
    {
        return $this->squares;
    }

    /**
     * @param ArrayCollection $squares
     */
    public function setSquares($squares): void
    {
        $this->squares = $squares;
    }

    public function addSquare(Square $square)
    {
        $this->squares[] = $square;
        return $this->squares;
    }

    /**
     * @return boolean
     */
    public function isSolved()
    {
        return $this->solved;
    }

    /**
     * @param bool $solved
     */
    public function setSolved($solved): void
    {
        $this->solved = $solved;
    }

    /*
     * TODO Cloner une grille
    public function __clone()
    {
        $this->rows = new ArrayCollection();
        $this->columns = new ArrayCollection();
        $this->squares = new ArrayCollection();
    }
    */
}