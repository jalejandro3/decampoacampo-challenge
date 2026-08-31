<?php

namespace Jalejandro\DecampoacampoChallenge\Application;

use Jalejandro\DecampoacampoChallenge\Model\Producto;

class ProductoPresenter
{
    public static function transformar(Producto $producto, float $tasaDolar): array
    {
        return [
            'id' => $producto->getId(),
            'nombre' => $producto->getNombre(),
            'descripcion' => $producto->getDescripcion(),
            'precio' => $producto->getPrecio(),
            'precio_usd' => $producto->precioEnDolares($tasaDolar),
        ];
    }
}
