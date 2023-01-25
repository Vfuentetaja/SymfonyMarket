<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductoRepository::class)]
class Producto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?float $precio = null;

    #[ORM\Column(length: 1500, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\Column(length: 255)]
    private ?string $categoria = null;

    #[ORM\Column(nullable: true)]
    private ?bool $destacado = null;

    #[ORM\Column(nullable: true)]
    private ?bool $oferta = null;

    #[ORM\Column(nullable: true)]
    private ?bool $novedad = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagen = null;

    #[ORM\Column(nullable: true)]
    private ?int $descuento = null;

    #[ORM\OneToMany(mappedBy: 'producto', targetEntity: Item::class)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

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

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function isDestacado(): ?bool
    {
        return $this->destacado;
    }

    public function setDestacado(?bool $destacado): self
    {
        $this->destacado = $destacado;

        return $this;
    }

    public function isOferta(): ?bool
    {
        return $this->oferta;
    }

    public function setOferta(?bool $oferta): self
    {
        $this->oferta = $oferta;

        return $this;
    }

    public function isNovedad(): ?bool
    {
        return $this->novedad;
    }

    public function setNovedad(?bool $novedad): self
    {
        $this->novedad = $novedad;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getDescuento(): ?int
    {
        return $this->descuento;
    }

    public function setDescuento(?int $descuento): self
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setProducto($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getProducto() === $this) {
                $item->setProducto(null);
            }
        }

        return $this;
    }
}
