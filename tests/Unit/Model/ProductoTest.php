<?php

namespace Tests\Unit\Model;

use Jalejandro\DecampoacampoChallenge\Exception\DatoInvalidoException;
use Tests\ObjectMother\ProductoMother;
use PHPUnit\Framework\TestCase;

class ProductoTest extends TestCase
{
    public function test_producto_precio_dolar_del_producto()
    {
        $producto = ProductoMother::create();

        $this->assertEquals(1.0, $producto->precioEnDolares(1000));
    }

    public function test_producto_precio_negativo_arroja_dato_invalido_exception()
    {
        $this->expectException(DatoInvalidoException::class);
        $this->expectExceptionMessageIs('El precio del producto debe ser mayor a 0.');
        ProductoMother::create(-1000.0);
    }

    public function test_producto_precio_cero_arroja_dato_invalido_exception()
    {
        $this->expectException(DatoInvalidoException::class);
        $this->expectExceptionMessageIs('El precio del producto debe ser mayor a 0.');
        ProductoMother::create(0.0);
    }

    public function test_producto_creado_retorna_valores_correctos()
    {
        $producto = ProductoMother::create();

        $this->assertEquals(1, $producto->getId());
        $this->assertEquals('Ganado', $producto->getNombre());
        $this->assertEquals('Maute', $producto->getDescripcion());
        $this->assertEquals(1000.0, $producto->getPrecio());
    }

    public function test_producto_creado_con_id_nulo_retorna_id_nulo()
    {
        $producto = ProductoMother::createConId(null);

        $this->assertNull($producto->getId());
    }

    public function test_producto_nombre_vacio_arroja_dato_invalido_exception()
    {
        $this->expectException(DatoInvalidoException::class);
        $this->expectExceptionMessageIs('El nombre del producto no puede estar vacío.');
        ProductoMother::createConNombre('');
    }

    public function test_producto_descripcion_vacio_arroja_dato_invalido_exception()
    {
        $this->expectException(DatoInvalidoException::class);
        $this->expectExceptionMessageIs('La descripción del producto no puede estar vacía.');
        ProductoMother::createConDescripcion('');
    }
}
