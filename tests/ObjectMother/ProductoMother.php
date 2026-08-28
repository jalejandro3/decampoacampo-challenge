<?php

namespace Tests\ObjectMother;

use Jalejandro\DecampoacampoChallenge\Model\Producto;

class ProductoMother
{
    public static function create(float $precio = 1000.0): Producto
    {
        return new Producto('Ganado', 'Maute', $precio);
    }
}