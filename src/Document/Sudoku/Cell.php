<?php

namespace App\Document\Sudoku;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="sudoku_db", collection="cell", repositoryClass="App\Repository\CellRepository")
 */
class Cell
{
    /**
     * @MongoDB\Id()
     */
    private $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="App\Document\Sudoku\Row", inversedBy="cells", storeAs="id")
     * @MongoDB\Index
     */
    private $row;

    /**
     * @MongoDB\ReferenceOne(targetDocument="App\Document\Sudoku\Column", inversedBy="cells", storeAs="id"))
     * @MongoDB\Index
     */
    private $column;

    /**
     * @MongoDB\ReferenceOne(targetDocument="App\Document\Sudoku\Square", inversedBy="cells", storeAs="id"))
     * @MongoDB\Index
     */
    private $square;

    /**
     * @MongoDB\Field(type="int", nullable="true")
     */
    private $value;

    /**
     * @MongoDB\Field(type="boolean")
     * @MongoDB\Index(partialFilterExpression={"empty"={"$eq"=true}})
     */
    private $empty;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Unit
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param Unit $row
     */
    public function setRow($row): void
    {
        $this->row = $row;
    }

    /**
     * @return Unit
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param Unit $column
     */
    public function setColumn($column): void
    {
        $this->column = $column;
    }

    /**
     * @return Unit
     */
    public function getSquare()
    {
        return $this->square;
    }

    /**
     * @param Unit $square
     */
    public function setSquare($square): void
    {
        $this->square = $square;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->empty;
    }

    /**
     * @param bool $empty
     */
    public function setEmpty($empty): void
    {
        $this->empty = $empty;
    }
}