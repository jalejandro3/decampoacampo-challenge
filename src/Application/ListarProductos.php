<?php

namespace Jalejandro\DecampoacampoChallenge\Application;

use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;

readonly class ListarProductos
{
    public function __construct(private Configuracion $configuracion, private ProductoRepository $productoRepository) {}

    public function execute(): array
    {
        $productos = $this->productoRepository->findAll();

        return array_map(function ($producto) {
            return ProductoPresenter::transformar($producto, $this->configuracion->getPrecioUsd());
        }, $productos);
    }
}
