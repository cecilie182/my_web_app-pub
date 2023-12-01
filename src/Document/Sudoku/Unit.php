<?php

namespace App\Document\Sudoku;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\MappedSuperclass()
 */
abstract class Unit
{
    /**
     * @MongoDB\Id()
     */
    private $id;

    /**
     * @MongoDB\Field(type="int")
     */
    private $index;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param int $index
     */
    public function setIndex($index): void
    {
        $this->index = $index;
    }

    public abstract function setGrid(Grid $grid);
}