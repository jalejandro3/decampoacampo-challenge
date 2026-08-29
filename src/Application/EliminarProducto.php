<?php

namespace Jalejandro\DecampoacampoChallenge\Application;

use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;

readonly class EliminarProducto
{
    public function __construct(private ProductoRepository $productoRepository) {}

    /**
     * @throws ProductoNoEncontradoException
     */
    public function execute(int $id): void
    {
        $result = $this->productoRepository->delete($id);

        if (!$result) {
            throw new ProductoNoEncontradoException('Producto no encontrado.');
        }
    }
}
