<?php

namespace Jalejandro\DecampoacampoChallenge\Http;

use Jalejandro\DecampoacampoChallenge\Application\ListarProductos;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class ListarProductosController
{
    public function __construct(private ListarProductos $listarProductos) {}

    public function __invoke(?Request $request = null): JsonResponse
    {
        $result = $this->listarProductos->execute();

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
