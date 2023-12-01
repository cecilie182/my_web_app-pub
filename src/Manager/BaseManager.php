<?php

namespace App\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;

abstract class BaseManager
{
    private $documentManager;
    private $documentName;

    public function __construct(DocumentManager $documentManager, string $documentName)
    {
        $this->documentManager = $documentManager;
        $this->documentName = $documentName;
    }

    public function save(Document $document)
    {
        $this->documentManager->persist($document);
        $this->documentManager->flush();
    }

    public function getRepository()
    {
        return $this->documentManager->getRepository($this->documentName);
    }
}