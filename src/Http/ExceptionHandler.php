<?php

namespace Jalejandro\DecampoacampoChallenge\Http;

use Jalejandro\DecampoacampoChallenge\Exception\DatoInvalidoException;
use Jalejandro\DecampoacampoChallenge\Exception\DatoRequeridoException;
use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ExceptionHandler
{
    public function handle(\Throwable $e): JsonResponse
    {
        return match($e::class) {
            DatoRequeridoException::class => new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST),
            DatoInvalidoException::class => new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY),
            ProductoNoEncontradoException::class => new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND),
            ResourceNotFoundException::class => new JsonResponse(['error' => 'Recurso no encontrado'], Response::HTTP_NOT_FOUND),
            default => new JsonResponse(['error' => 'Error interno del servidor'], Response::HTTP_INTERNAL_SERVER_ERROR),
        };
    }
}
