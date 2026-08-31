<?php

namespace Tests\Unit\Application;

use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Jalejandro\DecampoacampoChallenge\Application\Configuracion;
use Jalejandro\DecampoacampoChallenge\Application\MostrarProducto;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;
use PHPUnit\Framework\TestCase;
use Tests\ObjectMother\ProductoMother;

class MostrarProductoTest extends TestCase
{
    public function test_mostrar_producto_sin_producto_arroja_exception()
    {
        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);

        $productoRepository->method('findById')->willReturn(null);

        $mostrarProducto = new MostrarProducto($configuracion, $productoRepository);

        $this->expectException(ProductoNoEncontradoException::class);
        $this->expectExceptionMessageIs('El producto con id 1 no existe.');

        $mostrarProducto->execute(1);
    }

    public function test_mostrar_producto_con_producto_retorna_producto()
    {
        $producto = ProductoMother::create();
        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);

        $productoRepository->method('findById')->willReturn($producto);
        $configuracion->method('getPrecioUsd')->willReturn(1000.0);

        $mostrarProducto = new MostrarProducto($configuracion, $productoRepository);

        $result = $mostrarProducto->execute(1);

        $this->assertSame(1.0, $result['precio_usd']);
    }
}
