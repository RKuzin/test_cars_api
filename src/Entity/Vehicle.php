<?php

namespace App\Entity;

use App\Repository\VehiclesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VehiclesRepository::class)
 */
class Vehicle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=VehicleBrand::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isNew;

    /**
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    private $modelYear;

    /**
     * @ORM\Column(nullable=true, type="decimal", precision=18, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $hasRainSensor;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true, "default": 0})
     */
    private $mileage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?VehicleBrand
    {
        return $this->brand;
    }

    public function setBrand(?VehicleBrand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getIsNew(): ?bool
    {
        return $this->isNew;
    }

    public function setIsNew(bool $isNew): self
    {
        $this->isNew = $isNew;

        return $this;
    }

    public function getModelYear(): ?int
    {
        return $this->modelYear;
    }

    public function setModelYear(int $modelYear): self
    {
        $this->modelYear = $modelYear;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getHasRainSensor(): ?bool
    {
        return $this->hasRainSensor;
    }

    public function setHasRainSensor(bool $hasRainSensor): self
    {
        $this->hasRainSensor = $hasRainSensor;

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }
}
