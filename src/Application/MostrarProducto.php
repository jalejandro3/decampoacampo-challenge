<?php

namespace Jalejandro\DecampoacampoChallenge\Application;

use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;

readonly class MostrarProducto
{
    public function __construct(private Configuracion $configuracion, private ProductoRepository $productoRepository) {}

    /**
     * @throws ProductoNoEncontradoException
     */
    public function execute(int $id): array
    {
        $producto = $this->productoRepository->findById($id);

        if ($producto === null) {
            throw new ProductoNoEncontradoException("El producto con id $id no existe.");
        }

        return ProductoPresenter::transformar($producto, $this->configuracion->getPrecioUsd());
    }
}
