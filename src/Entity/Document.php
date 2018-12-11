<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
{
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $documentId;
    
    /**
     * @ORM\Column(type="integer", options={"default"=0}, nullable=true)
     */
    private $templateId;
    
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     */
    private $originalFile;
    
    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $scanParameter;
    
    /**
     * @ORM\Column(type="string", options={"default"="processing"}, nullable=true)
     */
    private $scanStatus = 'processing';
    
    /**
     * @var string|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $invoiceDate;
    
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $invoiceSeries;
    
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $invoiceNumber;
    
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $invoiceBuyerName;
    
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $invoiceBuyerAddress;
    
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $invoiceBuyerCode;
    
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $invoiceBuyerVatCode;
    
    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", scale=2, precision=10, nullable=true)
     */
    private $invoiceTotal;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createTime;
    
    
    /**
     */
    public function __construct()
    {
        $this->createTime = new \DateTime();
    }
    
    public function getDocumentId(): ?int
    {
        return $this->documentId;
    }
    
    public function getTemplateId(): ?int
    {
        return $this->templateId;
    }
    
    public function setTemplateId(int $templateId): self
    {
        $this->templateId = $templateId;
        
        return $this;
    }
    
    public function getOriginalFile(): ?string
    {
        return $this->originalFile;
    }
    
    public function setOriginalFile(string $originalFile): self
    {
        $this->originalFile = $originalFile;
        
        return $this;
    }
    
    public function getScanParameter()
    {
        return $this->scanParameter;
    }
    
    public function setScanParameter($scanParameter): self
    {
        $this->scanParameter = $scanParameter;
        
        return $this;
    }
    
    public function getScanStatus(): ?string
    {
        return $this->scanStatus;
    }
    
    public function setScanStatus(string $scanStatus): self
    {
        if (!in_array($scanStatus, array(self::STATUS_PROCESSING, self::STATUS_ERROR, self::STATUS_SUCCESS))) {
            throw new \InvalidArgumentException("processing");
        }
        $this->scanStatus = $scanStatus;
        
        return $this;
    }
    
    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoiceDate;
    }
    
    public function setInvoiceDate(?\DateTimeInterface $invoiceDate): self
    {
        $this->invoiceDate = $invoiceDate;
        
        return $this;
    }
    
    public function getInvoiceSeries(): ?string
    {
        return $this->invoiceSeries;
    }
    
    public function setInvoiceSeries(?string $invoiceSeries): self
    {
        $this->invoiceSeries = $invoiceSeries;
        
        return $this;
    }
    
    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }
    
    public function setInvoiceNumber(?string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;
        
        return $this;
    }
    
    public function getInvoiceBuyerName(): ?string
    {
        return $this->invoiceBuyerName;
    }
    
    public function setInvoiceBuyerName(?string $invoiceBuyerName): self
    {
        $this->invoiceBuyerName = $invoiceBuyerName;
        
        return $this;
    }
    
    public function getInvoiceBuyerAddress(): ?string
    {
        return $this->invoiceBuyerAddress;
    }
    
    public function setInvoiceBuyerAddress(?string $invoiceBuyerAddress): self
    {
        $this->invoiceBuyerAddress = $invoiceBuyerAddress;
        
        return $this;
    }
    
    public function getInvoiceBuyerCode(): ?string
    {
        return $this->invoiceBuyerCode;
    }
    
    public function setInvoiceBuyerCode(?string $invoiceBuyerCode): self
    {
        $this->invoiceBuyerCode = $invoiceBuyerCode;
        
        return $this;
    }
    
    public function getInvoiceBuyerVatCode(): ?string
    {
        return $this->invoiceBuyerVatCode;
    }
    
    public function setInvoiceBuyerVatCode(?string $invoiceBuyerVatCode): self
    {
        $this->invoiceBuyerVatCode = $invoiceBuyerVatCode;
        
        return $this;
    }
    
    public function getInvoiceTotal()
    {
        return $this->invoiceTotal;
    }
    
    public function setInvoiceTotal($invoiceTotal): self
    {
        $this->invoiceTotal = $invoiceTotal;
        
        return $this;
    }
    
    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }
}
