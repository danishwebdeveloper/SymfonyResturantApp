<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ordertable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ordername;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ornernumber;

    /**
     * @ORM\Column(type="float")
     */
    private $orderprice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $orderstatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdertable(): ?string
    {
        return $this->ordertable;
    }

    public function setOrdertable(string $ordertable): self
    {
        $this->ordertable = $ordertable;

        return $this;
    }

    public function getOrdername(): ?string
    {
        return $this->ordername;
    }

    public function setOrdername(string $ordername): self
    {
        $this->ordername = $ordername;

        return $this;
    }

    public function getOrnernumber(): ?string
    {
        return $this->ornernumber;
    }

    public function setOrnernumber(string $ornernumber): self
    {
        $this->ornernumber = $ornernumber;

        return $this;
    }

    public function getOrderprice(): ?float
    {
        return $this->orderprice;
    }

    public function setOrderprice(float $orderprice): self
    {
        $this->orderprice = $orderprice;

        return $this;
    }

    public function getOrderstatus(): ?string
    {
        return $this->orderstatus;
    }

    public function setOrderstatus(string $orderstatus): self
    {
        $this->orderstatus = $orderstatus;

        return $this;
    }
}
