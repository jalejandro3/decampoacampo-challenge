<?php

namespace Tests\Unit\Application;

use Jalejandro\DecampoacampoChallenge\Application\Configuracion;
use Jalejandro\DecampoacampoChallenge\Application\CrearProducto;
use Jalejandro\DecampoacampoChallenge\Exception\DatoInvalidoException;
use Jalejandro\DecampoacampoChallenge\Model\Producto;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;
use PHPUnit\Framework\TestCase;

class CrearProductoTest extends TestCase
{
    public function test_crear_producto_con_datos_correctos_retorna_producto_con_id_persistido()
    {
        $nombre = 'Ganado';
        $descripcion = 'Maute';
        $precio = 1000.0;
        $productoRepository = $this->createMock(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);

        $configuracion->method('getPrecioUsd')->willReturn(1000.0);
        $productoRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Producto $producto) use ($nombre, $descripcion, $precio) {
                return $producto->getId() === null &&
                    $producto->getNombre() === $nombre &&
                    $producto->getDescripcion() === $descripcion &&
                    $producto->getPrecio() === $precio;
            }))
            ->willReturn(34);

        $crearProducto = new CrearProducto($configuracion, $productoRepository);
        $resultado = $crearProducto->execute($nombre, $descripcion, $precio);

        $this->assertSame(34, $resultado['id']);
    }

    public function test_crear_producto_con_datos_invalidos_propaga_excepcion_y_no_persiste()
    {
        $productoRepository = $this->createMock(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);

        $productoRepository->expects($this->never())->method('save');

        $crearProducto = new CrearProducto($configuracion, $productoRepository);

        $this->expectException(DatoInvalidoException::class);

        $crearProducto->execute('', 'Maute', 1000.0);
    }
}
