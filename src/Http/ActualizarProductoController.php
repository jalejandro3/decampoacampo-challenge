<?php

namespace Jalejandro\DecampoacampoChallenge\Http;

use Jalejandro\DecampoacampoChallenge\Application\ActualizarProducto;
use Jalejandro\DecampoacampoChallenge\Exception\DatoInvalidoException;
use Jalejandro\DecampoacampoChallenge\Exception\DatoRequeridoException;
use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class ActualizarProductoController
{
    public function __construct(private ActualizarProducto $actualizarProducto) {}

    /**
     * @throws DatoRequeridoException
     * @throws DatoInvalidoException
     * @throws ProductoNoEncontradoException
     */
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null || !isset($data['nombre'], $data['descripcion'], $data['precio'])) {
            throw new DatoRequeridoException('Datos requeridos incompletos');
        }

        $resultado = $this->actualizarProducto->execute($id, $data['nombre'], $data['descripcion'], (float) $data['precio']);

        return new JsonResponse($resultado, Response::HTTP_OK);
    }
}
