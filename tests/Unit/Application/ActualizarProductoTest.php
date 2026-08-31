<?php

namespace Tests\Unit\Application;

use Jalejandro\DecampoacampoChallenge\Application\ActualizarProducto;
use Jalejandro\DecampoacampoChallenge\Application\Configuracion;
use Jalejandro\DecampoacampoChallenge\Exception\DatoInvalidoException;
use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Jalejandro\DecampoacampoChallenge\Model\Producto;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;
use PHPUnit\Framework\TestCase;

class ActualizarProductoTest extends TestCase
{
    public function test_actualizar_producto()
    {
        $productoRepository = $this->createMock(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);
        $producto = new Producto(1, 'Cerdo', 'Lechon', 50000.0);

        $productoRepository->method('findById')->willReturn($producto);

        $productoRepository
            ->expects($this->once())
            ->method('update')
            ->with($this->callback(function (Producto $productoActualizado) {
                return $productoActualizado->getId() === 1 &&
                    $productoActualizado->getNombre() === 'Ganado' &&
                    $productoActualizado->getDescripcion() === 'Maute' &&
                    $productoActualizado->getPrecio() === 1000.0;
            }));

        $configuracion->method('getPrecioUsd')->willReturn(1000.0);

        $actualizarProducto = new ActualizarProducto($configuracion, $productoRepository);
        $resultado = $actualizarProducto->execute(1, 'Ganado', 'Maute', 1000.0);

        $this->assertSame(1000.0, $resultado['precio']);
    }

    public function test_actualizar_producto_con_id_no_existente_propaga_excepcion()
    {
        $productoRepository = $this->createMock(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);

        $productoRepository->method('findById')->willReturn(null);
        $productoRepository->expects($this->never())->method('update');

        $actualizarProducto = new ActualizarProducto($configuracion, $productoRepository);

        $this->expectException(ProductoNoEncontradoException::class);
        $this->expectExceptionMessageIs('El producto con id 1 no existe.');

        $actualizarProducto->execute(1, 'Ganado', 'Maute', 1000.0);
    }

    public function test_actualizar_producto_con_datos_invalidos_propaga_excepcion_y_no_actualiza()
    {
        $productoRepository = $this->createMock(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);
        $producto = new Producto(1, 'Cerdo', 'Lechon', 50000.0);

        $productoRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($producto);

        $productoRepository
            ->expects($this->never())
            ->method('update');

        $actualizarProducto = new ActualizarProducto($configuracion, $productoRepository);

        $this->expectException(DatoInvalidoException::class);

        $actualizarProducto->execute(1, '', 'Maute', 1000.0);
    }
}
