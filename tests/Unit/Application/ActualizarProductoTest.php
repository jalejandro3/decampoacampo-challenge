<?php

namespace Tests\Unit\Application;

use Jalejandro\DecampoacampoChallenge\Application\ActualizarProducto;
use Jalejandro\DecampoacampoChallenge\Application\Configuracion;
use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Jalejandro\DecampoacampoChallenge\Model\Producto;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;
use PHPUnit\Framework\TestCase;

class ActualizarProductoTest extends TestCase
{
    public function test_actualizar_producto()
    {
        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);
        $producto = new Producto(1, 'Cerdo', 'Lechon', 50000.0);

        $productoRepository->method('findById')->willReturn($producto);
        $productoRepository->method('update');
        $configuracion->method('getPrecioUsd')->willReturn(1000.0);

        $actualizarProducto = new ActualizarProducto($configuracion, $productoRepository);
        $resultado = $actualizarProducto->execute(1, 'Ganado', 'Maute', 1000.0);

        $this->assertCount(5, $resultado);

        $this->assertArrayHasKey('id', $resultado);
        $this->assertArrayHasKey('nombre', $resultado);
        $this->assertArrayHasKey('descripcion', $resultado);
        $this->assertArrayHasKey('precio', $resultado);
        $this->assertArrayHasKey('precio_usd', $resultado);

        $this->assertSame('Ganado', $resultado['nombre']);
        $this->assertSame('Maute', $resultado['descripcion']);
        $this->assertSame(1000.0, $resultado['precio']);
        $this->assertSame(1.0, $resultado['precio_usd']);
    }

    public function test_actualizar_producto_con_id_no_existente_retorna_producto_no_encontrado_exception()
    {
        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);

        $productoRepository->method('findById')->willReturn(null);

        $actualizarProducto = new ActualizarProducto($configuracion, $productoRepository);

        $this->expectException(ProductoNoEncontradoException::class);
        $this->expectExceptionMessageIs('El producto con id 1 no existe.');

        $actualizarProducto->execute(1, 'Ganado', 'Maute', 1000.0);
    }
}
