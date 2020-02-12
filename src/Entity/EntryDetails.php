<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntryDetailsRepository")
 */
class EntryDetails
{
    /**
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $startMillage;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $endMillage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isTire;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $qty;


    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $price;


    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SpareParts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $spareParts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car")
     * @ORM\JoinColumn(nullable=false)
     */
    private $car;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="smallint",  options={{"comment":" 0 = inactive, 1 = active, 2 = wastage,3 = sold "},{"default" : 1}})
     */
    private $status = 1;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"comment":"1 = Solid, 2 = Liquid "})
     */
    private $isQuantityNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $partsDescription;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $wastageDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $soldDate;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $soldPrice;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"comment":"1 = Front Left, 2 = Front Right, 3 = Back Left 1, 4 = Back Left 2, 5 = Back Right 1, 6 = Back Right 2, 7 = Free Spears Tire "})
     */
    private $tirePosition;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    public function __construct()
    {


    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStartMillage(): ?string
    {
        return $this->startMillage;
    }

    public function setStartMillage(?string $startMillage): self
    {
        $this->startMillage = $startMillage;

        return $this;
    }

    public function getEndMillage(): ?string
    {
        return $this->endMillage;
    }

    public function setEndMillage(?string $endMillage): self
    {
        $this->endMillage = $endMillage;

        return $this;
    }

    public function getIsTire(): ?bool
    {
        return $this->isTire;
    }

    public function setIsTire(?bool $isTire): self
    {
        $this->isTire = $isTire;

        return $this;
    }

    public function getQty(): ?string
    {
        return $this->qty;
    }

    public function setQty(?string $qty): self
    {
        $this->qty = $qty;

        return $this;
    }


    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }




    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
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

    public function getSpareParts(): ?SpareParts
    {
        return $this->spareParts;
    }

    public function setSpareParts(?SpareParts $spareParts): self
    {
        $this->spareParts = $spareParts;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsQuantityNumber(): ?bool
    {
        return $this->isQuantityNumber;
    }

    public function setIsQuantityNumber(?bool $isQuantityNumber): self
    {
        $this->isQuantityNumber = $isQuantityNumber;

        return $this;
    }

    public function getPartsDescription(): ?string
    {
        return $this->partsDescription;
    }

    public function setPartsDescription(?string $partsDescription): self
    {
        $this->partsDescription = $partsDescription;

        return $this;
    }

    public function getWastageDate(): ?\DateTimeInterface
    {
        return $this->wastageDate;
    }

    public function setWastageDate(?\DateTimeInterface $wastageDate): self
    {
        $this->wastageDate = $wastageDate;

        return $this;
    }

    public function getSoldDate(): ?\DateTimeInterface
    {
        return $this->soldDate;
    }

    public function setSoldDate(?\DateTimeInterface $soldDate): self
    {
        $this->soldDate = $soldDate;

        return $this;
    }

    public function getSoldPrice(): ?string
    {
        return $this->soldPrice;
    }

    public function setSoldPrice(?string $soldPrice): self
    {
        $this->soldPrice = $soldPrice;

        return $this;
    }

    public function getTirePosition(): ?int
    {
        return $this->tirePosition;
    }

    public function setTirePosition(?int $tirePosition): self
    {
        $this->tirePosition = $tirePosition;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }


}
