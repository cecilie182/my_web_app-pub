<?php

namespace App\Repository;

use App\Document\Sudoku\Grid;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class UnitRepository extends DocumentRepository
{
    /**
     * @param Grid $grid
     * @param int $index
     * @return Builder
     */
    public function findByGridAndPosition(Grid $grid, int $index)
    {
        return $this->createQueryBuilder()
            ->field('grid')->references($grid)
            ->field('index')->equals($index);
    }
}