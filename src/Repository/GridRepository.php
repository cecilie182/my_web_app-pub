<?php

namespace App\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class GridRepository extends DocumentRepository
{
    /*
    public function findAllOrderedByName()
    {
        return $this->createQueryBuilder()
            ->sort('name', 'ASC')
            ->getQuery()
            ->execute();
    }
    */
}