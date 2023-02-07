<?php

namespace App\Service;

use App\Entity\Pedido;

class PedidoService
{
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

}

?>