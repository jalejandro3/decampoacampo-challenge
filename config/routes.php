<?php

use Jalejandro\DecampoacampoChallenge\Http\MostrarProductoController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$mostrarProductoRoute = new Route('/productos/{id}')
    ->setMethods(['GET'])
    ->setRequirements(['id' => '\d+'])
    ->setDefaults(['_controller' => MostrarProductoController::class]);

$routes = new RouteCollection();

$routes->add('mostrar_producto', $mostrarProductoRoute);

return $routes;