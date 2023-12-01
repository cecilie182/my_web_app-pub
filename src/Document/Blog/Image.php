<?php

namespace App\Document\Blog;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="blog", collection="image")
 */
class Image
{
    /**
     * @MongoDB\Id()
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}