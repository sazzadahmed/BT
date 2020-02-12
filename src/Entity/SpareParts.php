<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SparePartsRepository")
 */
class SpareParts
{
    /**
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true )
     */
    private $mileage;

    /**
     * @ORM\Column(type="boolean", nullable=true )
     */
    private $isTire;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"comment":"1 = Solid, 2 = Liquid "})
     */
    private $NumOrQty;

    /**
     * @ORM\Column(type="datetime", nullable=true )
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="boolean",  options={{"comment":"1 = active, 0 = inactive "},{"default" : true}})
     */
    private $status = true;

    public function __construct()
    {
        $this->createAt = new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMileage(): ?string
    {
        return $this->mileage;
    }

    public function setMileage(string $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }


    public function getIsTire(): ?bool
    {
        return $this->isTire;
    }

    public function setIsTire(bool $isTire): self
    {
        $this->isTire = $isTire;

        return $this;
    }

    public function getNumOrQty(): ?int
    {
        return $this->NumOrQty;
    }

    public function setNumOrQty(?int $NumOrQty): self
    {
        $this->NumOrQty = $NumOrQty;

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
