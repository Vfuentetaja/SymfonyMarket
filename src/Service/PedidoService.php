<?php

namespace App\Service;

use App\Entity\Pedido;
use App\Entity\Item;
use App\Entity\Producto;
use App\Repository\ProductoRepository;

class PedidoService
{
    private $productoRepository;
    public function __construct(ProductoRepository $productoRepository){
        $this->productoRepository=$productoRepository;
    }

    public function calcularTotal(Pedido $pedido):float{
        $total=0;
        $items=$pedido->getItems();
        foreach ($items as $item){
            $precio=$item->getProducto()->getPrecio();
            ($item->getProducto()->getDescuento()!=null)? $descuento=$item->getProducto()->getDescuento(): $descuento=0;
            $cantidad=$item->getCantidad();
            $total+=($precio*$cantidad)-(($precio/100)*$descuento*$cantidad);

        }
        return $total;
    }

    public function modificarCantidadProductoDisponiblePedido(Pedido $pedido){
        foreach ($pedido->getItems() as $item) {
            $producto= $item->getProducto();
            $cantidad= $item->getCantidad();
            $producto->setCantidad ($producto->getCantidad()-$cantidad);
            $this->productoRepository->save($producto, true);
        }
    }

    public function modificarCantidadProductoDisponibleItem(Item $item){
        $producto= $item->getProducto();
        $cantidad= $item->getCantidad();
        $producto->setCantidad ($producto->getCantidad()+$cantidad);
        $this->productoRepository->save($producto, true);
    }

}

?>