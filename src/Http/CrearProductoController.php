<?php

namespace Jalejandro\DecampoacampoChallenge\Http;

use Jalejandro\DecampoacampoChallenge\Exception\DatoInvalidoException;
use Jalejandro\DecampoacampoChallenge\Exception\DatoRequeridoException;
use Jalejandro\DecampoacampoChallenge\Application\CrearProducto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class CrearProductoController
{
    public function __construct(private CrearProducto $crearProducto) {}

    /**
     * @throws DatoRequeridoException
     * @throws DatoInvalidoException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null || !isset($data['nombre'], $data['descripcion'], $data['precio'])) {
            throw new DatoRequeridoException('Datos requeridos incompletos');
        }

        $result = $this->crearProducto->execute($data['nombre'], $data['descripcion'], (float) $data['precio']);

        return new JsonResponse($result, Response::HTTP_CREATED);
    }
}
