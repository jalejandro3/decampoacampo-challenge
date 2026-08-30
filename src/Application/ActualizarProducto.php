<?php

namespace Jalejandro\DecampoacampoChallenge\Application;

use Jalejandro\DecampoacampoChallenge\Exception\DatoInvalidoException;
use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Jalejandro\DecampoacampoChallenge\Model\Producto;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;

class ActualizarProducto
{
    public function __construct(private Configuracion $configuracion, private ProductoRepository $productoRepository) {}

    /**
     * @throws DatoInvalidoException
     * @throws ProductoNoEncontradoException
     */
    public function execute(int $id, string $nombre, string $descripcion, float $precio): array
    {
        if ($this->productoRepository->findById($id) === null) {
            throw new ProductoNoEncontradoException("El producto con id $id no existe.");
        }

        $producto = new Producto($id, $nombre, $descripcion, $precio);

        $this->productoRepository->update($producto);

        return ProductoPresenter::transformar($producto, $this->configuracion->getPrecioUsd());
    }
}