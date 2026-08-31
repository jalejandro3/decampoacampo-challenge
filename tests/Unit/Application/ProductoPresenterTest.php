<?php

namespace Tests\Unit\Application;

use Jalejandro\DecampoacampoChallenge\Application\ProductoPresenter;

use PHPUnit\Framework\TestCase;
use Tests\ObjectMother\ProductoMother;

class ProductoPresenterTest extends TestCase
{
    public function test_producto_presenter_transformar_producto_retorna_arreglo_con_datos_transformados()
    {
        $producto = ProductoMother::create(100000.0);
        $resultado = ProductoPresenter::transformar($producto, 1000.0);

        $this->assertSame(1, $resultado['id']);
        $this->assertEquals('Ganado', $resultado['nombre']);
        $this->assertEquals('Maute', $resultado['descripcion']);
        $this->assertSame(100000.0, $resultado['precio']);
        $this->assertSame(100.0, $resultado['precio_usd']);
    }
}
