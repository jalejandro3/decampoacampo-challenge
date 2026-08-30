<?php

namespace Unit\Http;

use Jalejandro\DecampoacampoChallenge\Exception\DatoInvalidoException;
use Jalejandro\DecampoacampoChallenge\Exception\DatoRequeridoException;
use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Jalejandro\DecampoacampoChallenge\Http\ExceptionHandler;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ExceptionHandlerTest extends TestCase
{
    public function test_exception_handler_retorna_json_con_producto_no_encontrado_exception()
    {
        $exception = new ProductoNoEncontradoException('El producto con id 1 no existe.');
        $exceptionHandler = new ExceptionHandler();
        $response = $exceptionHandler->handle($exception);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'El producto con id 1 no existe.']), $response->getContent());
    }

    public function test_exception_handler_excepcion_no_mapeada_retorna_mensaje_generico()
    {
        $exception = new RuntimeException('SQLSTATE: acceso denegado con password root');
        $exceptionHandler = new ExceptionHandler();
        $response = $exceptionHandler->handle($exception);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'Error interno del servidor']), $response->getContent());
    }

    public function test_exception_handler_resource_not_found_exception_retorna_mensaje_generico()
    {
        $exception = new ResourceNotFoundException('Ruta no encontrada para "/xyz"');
        $exceptionHandler = new ExceptionHandler();
        $response = $exceptionHandler->handle($exception);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'Recurso no encontrado']), $response->getContent());
    }

    public function test_exception_handler_dato_requerido_exception()
    {
        $exception = new DatoRequeridoException('Datos requeridos incompletos');
        $exceptionHandler = new ExceptionHandler();
        $response = $exceptionHandler->handle($exception);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'Datos requeridos incompletos']), $response->getContent());
    }

    public function test_exception_handler_dato_invalido_exception()
    {
        $exception = new DatoInvalidoException('El nombre del producto no puede estar vacío.');
        $exceptionHandler = new ExceptionHandler();
        $response = $exceptionHandler->handle($exception);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'El nombre del producto no puede estar vacío.']), $response->getContent());
    }
}
