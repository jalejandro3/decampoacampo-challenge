<?php

namespace Jalejandro\DecampoacampoChallenge\Application;

use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;

class MostrarProducto
{
    public function __construct(private readonly Configuracion $configuracion,private readonly ProductoRepository $productoRepository) {}

    /**
     * @throws ProductoNoEncontradoException
     */
    public function execute(int $productoId): array
    {
        $producto = $this->productoRepository->findById($productoId);

        if ($producto === null) {
            throw new ProductoNoEncontradoException("El producto con id $productoId no existe.");
        }

        $result = $producto->toArray();
        $result['precio_usd'] = $producto->precioEnDolares($this->configuracion->getPrecioUsd());

        return $result;
    }
}
