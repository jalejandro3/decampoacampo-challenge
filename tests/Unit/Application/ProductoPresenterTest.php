<?php

namespace Tests\Unit\Application;

use Jalejandro\DecampoacampoChallenge\Application\ProductoPresenter;

use PHPUnit\Framework\TestCase;
use Tests\ObjectMother\ProductoMother;

class ProductoPresenterTest extends TestCase
{
    public function test_producto_presenter_transformar_producto_retorna_array_con_precio_usd()
    {
        $producto = ProductoMother::create(100000.0);
        $resultado = ProductoPresenter::transformar($producto, 1000.0);

        $this->assertArrayHasKey('precio_usd', $resultado);
        $this->assertEquals('Ganado', $resultado['nombre']);
        $this->assertSame(100.0, $resultado['precio_usd']);
    }
}
