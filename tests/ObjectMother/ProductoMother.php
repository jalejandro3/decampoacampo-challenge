<?php

namespace Tests\ObjectMother;

use Jalejandro\DecampoacampoChallenge\Model\Producto;

class ProductoMother
{
    public static function create(float $precio = 1000.0): Producto
    {
        return new Producto(1, 'Ganado', 'Maute', $precio);
    }

    public static function createConId(?int $id = 1): Producto
    {
        return new Producto($id, 'Ganado', 'Maute', 1000.0);
    }
}
