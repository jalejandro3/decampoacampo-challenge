<?php

namespace Jalejandro\DecampoacampoChallenge\Application;

use Jalejandro\DecampoacampoChallenge\Model\Producto;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;

readonly class CrearProducto
{
    public function __construct(private Configuracion $configuracion, private ProductoRepository $productoRepository) {}

    public function execute(string $nombre, string $descripcion, float $precio): array
    {
        $producto = new Producto(null, $nombre, $descripcion, $precio);

        $id = $this->productoRepository->save($producto);

        $nuevoProducto = new Producto($id, $nombre, $descripcion, $precio);

        return ProductoPresenter::transformar($nuevoProducto, $this->configuracion->getPrecioUsd());
    }
}
