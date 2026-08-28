<?php

namespace Tests\Unit\Model;

use Jalejandro\DecampoacampoChallenge\Exception\PrecioInvalidoException;
use Tests\ObjectMother\ProductoMother;
use PHPUnit\Framework\TestCase;

class ProductoTest extends TestCase
{
    public function test_producto_precio_dolar_del_producto()
    {
        $producto = ProductoMother::create();

        $this->assertEquals(1.0, $producto->precioEnDolares(0.001));
    }

    public function test_producto_precio_negativo_arroja_precio_invalido_exception()
    {
        $this->expectException(PrecioInvalidoException::class);
        $this->expectExceptionMessageIs('El precio del producto debe ser mayor a 0.');
        ProductoMother::create(-1000.0);
    }

    public function test_producto_precio_cero_arroja_precio_invalido_exception()
    {
        $this->expectException(PrecioInvalidoException::class);
        $this->expectExceptionMessageIs('El precio del producto debe ser mayor a 0.');
        ProductoMother::create(0.0);
    }

    public function test_producto_creado_retorna_arreglo_con_valores_correctos()
    {
        $producto = ProductoMother::create();
        $productoArray = $producto->toArray();

        $this->assertIsArray($productoArray);
        $this->assertCount(3, $productoArray);

        $this->assertArrayHasKey('nombre', $productoArray);
        $this->assertArrayHasKey('descripcion', $productoArray);
        $this->assertArrayHasKey('precio', $productoArray);

        $this->assertEquals('Ganado', $productoArray['nombre']);
        $this->assertEquals('Maute', $productoArray['descripcion']);
        $this->assertEquals(1000.0, $productoArray['precio']);
    }
}
