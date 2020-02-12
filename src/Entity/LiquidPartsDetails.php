<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LiquidPartsDetailsRepository")
 */
class LiquidPartsDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wastageQuantity = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $soldQuantity = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $soldPrice = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SpareParts")
     */
    private $spearsParts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car")
     */
    private $car;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="boolean", options={{"comment":"1 = active, 0 = inactive "},{"default" : true}})
     */
    private $status = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWastageQuantity(): ?int
    {
        return $this->wastageQuantity;
    }

    public function setWastageQuantity(?int $wastageQuantity): self
    {
        $this->wastageQuantity = $wastageQuantity;

        return $this;
    }

    public function getSoldQuantity(): ?int
    {
        return $this->soldQuantity;
    }

    public function setSoldQuantity(int $soldQuantity): self
    {
        $this->soldQuantity = $soldQuantity;

        return $this;
    }

    public function getSoldPrice(): ?int
    {
        return $this->soldPrice;
    }

    public function setSoldPrice(?int $soldPrice): self
    {
        $this->soldPrice = $soldPrice;

        return $this;
    }

    public function getSpearsParts(): ?SpareParts
    {
        return $this->spearsParts;
    }

    public function setSpearsParts(?SpareParts $spearsParts): self
    {
        $this->spearsParts = $spearsParts;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

}
