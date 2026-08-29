<?php

namespace Jalejandro\DecampoacampoChallenge\Http;

use Jalejandro\DecampoacampoChallenge\Application\EliminarProducto;
use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class EliminarProductoController
{
    public function __construct(private EliminarProducto $eliminarProducto) {}

    /**
     * @throws ProductoNoEncontradoException
     */
    public function __invoke(int $id, ?Request $request = null): JsonResponse
    {
        $this->eliminarProducto->execute($id);

        return new JsonResponse()->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
