<?php

namespace App\DataFixtures;

use App\Producto;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i<20;$i++){
            $producto= new Producto();
            $producto->setNombre('producto'+$i);
            $producto->setPrecio($i);
            $producto->setCantidad($i+2);
            $producto->setCategoria('Ropa');
            $manager->persist($producto);
        }
        $manager->flush();
    }
}
