<?php

namespace Tests\Unit\Application;

use Jalejandro\DecampoacampoChallenge\Application\EliminarProducto;
use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;
use PHPUnit\Framework\TestCase;

class EliminarProductoTest extends TestCase
{
    public function test_eliminar_producto_con_producto_inexistente_arroja_exception()
    {
        $productoRepository = $this->createStub(ProductoRepository::class);

        $productoRepository->method('delete')->willReturn(false);

        $eliminarProducto = new EliminarProducto($productoRepository);

        $this->expectException(ProductoNoEncontradoException::class);
        $this->expectExceptionMessageIs('Producto no encontrado.');

        $eliminarProducto->execute(1);
    }

    public function test_eliminar_producto_con_producto_existente_no_arroja_exception()
    {
        $productoRepository = $this->createStub(ProductoRepository::class);
        $productoRepository->method('delete')->willReturn(true);

        $eliminarProducto = new EliminarProducto($productoRepository);
        $eliminarProducto->execute(1);

        $this->expectNotToPerformAssertions();
    }
}
