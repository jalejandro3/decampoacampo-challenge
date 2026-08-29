<?php

namespace Tests\Unit\Application;

use Jalejandro\DecampoacampoChallenge\Application\Configuracion;
use Jalejandro\DecampoacampoChallenge\Application\CrearProducto;
use Jalejandro\DecampoacampoChallenge\Exception\DatoInvalidoException;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;
use PHPUnit\Framework\TestCase;

class CrearProductoTest extends TestCase
{
    public function test_crear_producto_con_datos_correctos_retorna_array_de_producto_nuevo()
    {
        $id = 1;
        $nombre = 'Ganado';
        $descripcion = 'Maute';
        $precio = 1000.0;
        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);

        $configuracion->method('getPrecioUsd')->willReturn(1000.0);
        $productoRepository->method('save')->willReturn($id);

        $crearProducto = new CrearProducto($configuracion, $productoRepository);
        $resultado = $crearProducto->execute($nombre, $descripcion, $precio);

        $this->assertIsArray($resultado);
        $this->assertCount(5, $resultado);

        $this->assertArrayHasKey('id', $resultado);
        $this->assertArrayHasKey('nombre', $resultado);
        $this->assertArrayHasKey('descripcion', $resultado);
        $this->assertArrayHasKey('precio', $resultado);
        $this->assertArrayHasKey('precio_usd', $resultado);

        $this->assertSame(1, $resultado['id']);
        $this->assertEquals('Ganado', $resultado['nombre']);
        $this->assertEquals('Maute', $resultado['descripcion']);
        $this->assertEquals(1000.0, $resultado['precio']);
        $this->assertSame(1.0, $resultado['precio_usd']);
    }

    public function test_crear_producto_con_nombre_vacio_retorna_dato_invalido_exception()
    {
        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);
        $crearProducto = new CrearProducto($configuracion, $productoRepository);

        $this->expectException(DatoInvalidoException::class);
        $this->expectExceptionMessageIs('El nombre del producto no puede estar vacío.');

        $crearProducto->execute('', 'Maute', 1000.0);
    }

    public function test_crear_producto_con_descripcion_vacia_retorna_dato_invalido_exception()
    {
        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);
        $crearProducto = new CrearProducto($configuracion, $productoRepository);

        $this->expectException(DatoInvalidoException::class);
        $this->expectExceptionMessageIs('La descripción del producto no puede estar vacía.');

        $crearProducto->execute('Ganado', '', 1000.0);
    }

    public function test_crear_producto_con_precio_negativo_retorna_dato_invalido_exception()
    {
        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);
        $crearProducto = new CrearProducto($configuracion, $productoRepository);

        $this->expectException(DatoInvalidoException::class);
        $this->expectExceptionMessageIs('El precio del producto debe ser mayor a 0.');

        $crearProducto->execute('Ganado', 'Maute', -1000.0);
    }
}
