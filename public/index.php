<?php

use Jalejandro\DecampoacampoChallenge\Exception\ProductoNoEncontradoException;
use Jalejandro\DecampoacampoChallenge\Http\MostrarProductoController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require __DIR__ . '/../vendor/autoload.php';
$mostrarProducto = require __DIR__ . '/../bootstrap.php';
$routes = require __DIR__ . '/../config/routes.php';

$request = Request::createFromGlobals();
$context = new RequestContext()->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    $parameters = $matcher->match($request->getPathInfo());
    $controller = new MostrarProductoController($mostrarProducto);
    $response = $controller((int) $parameters['id']);
} catch (ResourceNotFoundException | ProductoNoEncontradoException $e) {
    $response = new JsonResponse(['error' => 'No encontrado'], Response::HTTP_NOT_FOUND);
}

$response->send();