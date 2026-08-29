<?php

namespace Jalejandro\DecampoacampoChallenge\Application;

use Jalejandro\DecampoacampoChallenge\Model\Producto;

class ProductoPresenter
{
    public static function transformar(Producto $producto, float $tasaDolar): array
    {
        $resultado = $producto->toArray();
        $resultado['precio_usd'] = $producto->precioEnDolares($tasaDolar);

        return $resultado;
    }
}
