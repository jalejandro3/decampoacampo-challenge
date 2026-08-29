<?php

namespace Jalejandro\DecampoacampoChallenge\Application;

use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;

class ListarProductos
{
    public function __construct(private readonly Configuracion $configuracion, private readonly ProductoRepository $productoRepository) {}

    public function execute(): array
    {
        $productos = $this->productoRepository->findAll();

        return array_map(function ($producto) {
            return ProductoPresenter::transformar($producto, $this->configuracion->getPrecioUsd());
        }, $productos);
    }
}
