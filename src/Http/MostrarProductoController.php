<?php

namespace Jalejandro\DecampoacampoChallenge\Http;

use Jalejandro\DecampoacampoChallenge\Application\MostrarProducto;
use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class MostrarProductoController
{
    public function __construct(private MostrarProducto $mostrarProducto) {}

    /**
     * @throws ProductoNoEncontradoException
     */
    public function __invoke(int $id, ?Request $request = null): JsonResponse
    {
        $result = $this->mostrarProducto->execute($id);

        return new JsonResponse($result, Response::HTTP_OK);
    }
}