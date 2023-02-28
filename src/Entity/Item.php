<?php

namespace App\Entity;

use App\Entity\Pedido;
use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Producto::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'producto_id', referencedColumnName: 'id', nullable: false)]
    private ?Producto $producto = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\ManyToOne(targetEntity: Pedido::class, inversedBy: 'Items')]
    #[ORM\JoinColumn(name: 'pedido_id', referencedColumnName: 'id', nullable: false)]
    private ?Pedido $pedido = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProducto(): ?Producto
    {
        return $this->producto;
    }

    public function setProducto(?Producto $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getPedido(): ?Pedido
    {
        return $this->pedido;
    }

    public function setPedido(?Pedido $pedido): self
    {
        $this->pedido = $pedido;

        return $this;
    }


}
