<?php

namespace Tests\Unit\Application;

use Jalejandro\DecampoacampoChallenge\Application\Configuracion;
use Jalejandro\DecampoacampoChallenge\Application\ListarProductos;
use Jalejandro\DecampoacampoChallenge\Model\Producto;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;
use PHPUnit\Framework\TestCase;

class ListarProductosTest extends TestCase
{
    public function test_listar_productos_sin_productos_retorna_array_vacio()
    {
        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);

        $productoRepository->method('findAll')->willReturn([]);

        $listarProductos = new ListarProductos($configuracion, $productoRepository);
        $resultado = $listarProductos->execute();

        $this->assertSame([], $resultado);
    }

    public function test_listar_productos_con_productos_retorna_array_de_productos()
    {
        $productoGanado = new Producto(1, 'Ganado', 'Maute', 100000.0);
        $productoCerdo = new Producto(2, 'Cerdo', 'Lechon', 50000.0);
        $productos = [$productoGanado, $productoCerdo];

        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);

        $productoRepository->method('findAll')->willReturn($productos);
        $configuracion->method('getPrecioUsd')->willReturn(1000.0);

        $listarProductos = new ListarProductos($configuracion, $productoRepository);
        $resultado = $listarProductos->execute();

        $this->assertCount(2, $resultado);
    }
}
