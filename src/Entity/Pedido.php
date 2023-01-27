<?php

namespace App\Entity;

use App\Repository\PedidoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidoRepository::class)]
class Pedido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha_pedido = null;

    #[ORM\Column]
    private ?int $id_usuario = null;
    //el nombre que le pasamos al hacer el mappedBy debe ser el de la tabla, no el de la clase
    #[ORM\OneToMany(mappedBy: 'pedido', targetEntity: Item::class,cascade: ['persist','remove'])]
    private Collection $Items;

    #[ORM\Column(length: 400, nullable: true)]
    private ?string $destinatario = null;

    #[ORM\Column(length: 600, nullable: true)]
    private ?string $direccionDestinatario = null;

    public function __construct()
    {
        $this->Items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaPedido(): ?\DateTimeInterface
    {
        return $this->fecha_pedido;
    }

    public function setFechaPedido(?\DateTimeInterface $fecha_pedido): self
    {
        $this->fecha_pedido = $fecha_pedido;

        return $this;
    }

    public function getIdUsuario(): ?int
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(int $id_usuario): self
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->Items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->Items->contains($item)) {
            $this->Items->add($item);
            $item->setPedido($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->Items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getPedido() === $this) {
                $item->setPedido(null);
            }
        }

        return $this;
    }

    public function getDestinatario(): ?string
    {
        return $this->destinatario;
    }

    public function setDestinatario(?string $destinatario): self
    {
        $this->destinatario = $destinatario;

        return $this;
    }

    public function getDireccionDestinatario(): ?string
    {
        return $this->direccionDestinatario;
    }

    public function setDireccionDestinatario(?string $direccionDestinatario): self
    {
        $this->direccionDestinatario = $direccionDestinatario;

        return $this;
    }
}
