<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $originalFilePath;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var float|null
     *
     * @ORM\Column(type="float", scale=2, precision=15, nullable=true)
     */
    private $finalSum;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $serialNr;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $requisites;

    /**
     * @var string|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $documentDate;


    /**
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalFilePath(): ?string
    {
        return $this->originalFilePath;
    }

    public function setOriginalFilePath(string $originalFilePath): self
    {
        $this->originalFilePath = $originalFilePath;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function getFinalSum(): ?float
    {
        return $this->finalSum;
    }

    public function setFinalSum(?float $finalSum): self
    {
        $this->finalSum = $finalSum;

        return $this;
    }

    public function getSerialNr(): ?int
    {
        return $this->serialNr;
    }

    public function setSerialNr(?int $serialNr): self
    {
        $this->serialNr = $serialNr;

        return $this;
    }

    public function getRequisites(): ?string
    {
        return $this->requisites;
    }

    public function setRequisites(?string $requisites): self
    {
        $this->requisites = $requisites;

        return $this;
    }

    public function getDocumentDate(): ?\DateTimeInterface
    {
        return $this->documentDate;
    }

    public function setDocumentDate(?\DateTimeInterface $documentDate): self
    {
        $this->documentDate = $documentDate;

        return $this;
    }
}
