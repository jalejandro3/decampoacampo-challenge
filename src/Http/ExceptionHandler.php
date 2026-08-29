<?php

namespace Jalejandro\DecampoacampoChallenge\Http;

use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ExceptionHandler
{
    public function handle(\Throwable $e): JsonResponse
    {
        return match($e::class) {
            ProductoNoEncontradoException::class => new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND),
            ResourceNotFoundException::class => new JsonResponse(['error' => 'Recurso no encontrado'], Response::HTTP_NOT_FOUND),
            default => new JsonResponse(['error' => 'Error interno del servidor'], Response::HTTP_INTERNAL_SERVER_ERROR),
        };
    }
}
