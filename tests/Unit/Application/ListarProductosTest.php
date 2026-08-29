<?php

namespace Tests\Unit\Application;

use Jalejandro\DecampoacampoChallenge\Application\Configuracion;
use Jalejandro\DecampoacampoChallenge\Application\ListarProductos;
use Jalejandro\DecampoacampoChallenge\Model\Producto;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;
use PHPUnit\Framework\TestCase;

class ListarProductosTest extends TestCase
{
    public function test_listar_productos_con_productos_no_existentes_retorna_array_vacio()
    {
        $productoRepository = $this->createStub(ProductoRepository::class);
        $configuracion = $this->createStub(Configuracion::class);

        $productoRepository->method('findAll')->willReturn([]);

        $listarProductos = new ListarProductos($configuracion, $productoRepository);
        $resultado = $listarProductos->execute();

        $this->assertIsArray($resultado);
        $this->assertEmpty($resultado);
    }

    public function test_listar_productos_con_productos_existentes_retorna_array_de_productos()
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

        $this->assertIsArray($resultado);
        $this->assertCount(2, $resultado);

        $productoGanadoArray = $resultado[0];

        $this->assertArrayHasKey('nombre', $productoGanadoArray);
        $this->assertArrayHasKey('descripcion', $productoGanadoArray);
        $this->assertArrayHasKey('precio', $productoGanadoArray);
        $this->assertArrayHasKey('precio_usd', $productoGanadoArray);

        $this->assertEquals('Ganado', $productoGanadoArray['nombre']);
        $this->assertEquals('Maute', $productoGanadoArray['descripcion']);
        $this->assertEquals(100000.0, $productoGanadoArray['precio']);
        $this->assertEquals(100.0, $productoGanadoArray['precio_usd']);
    }
}
