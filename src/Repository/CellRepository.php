<?php

namespace App\Repository;

use App\Document\Sudoku\Grid;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class CellRepository extends DocumentRepository
{
    /**
     * @param Grid $grid
     * @return Builder
     */
    public function findEmptyByGrid(Grid $grid)
    {
        return $this->createQueryBuilder()
            ->field('row')->in($grid->getRows()->toArray())
            ->field('empty')->equals(true);
    }
}