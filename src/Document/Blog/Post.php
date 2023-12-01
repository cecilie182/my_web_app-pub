<?php

namespace App\Document\Blog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="blog", collection="post")
 */
class Post
{
    /**
     * @MongoDB\Id()
     */
    private $id;

    /**
     * @MongoDB\Field(type="date")
     */
    private $creation_date;

    /**
     * @MongoDB\ReferenceMany(targetDocument="App\Document\Blog\Image", inversedBy="post", storeAs="id")
     */
    private $images;

    /**
     * @MongoDB\Field(type="boolean")
     */
    private $published;

    /**
     * @MongoDB\Field(type="string")
     */
    private $description;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * @param \DateTime $creation_date
     */
    public function setCreationDate($creation_date): void
    {
        $this->creation_date = $creation_date;
    }

    /**
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param boolean $published
     */
    public function setPublished($published): void
    {
        $this->published = $published;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    public function addImage(Image $image)
    {
        $this->images[] = $image;
        return $this->images;
    }
}