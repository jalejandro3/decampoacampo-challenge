<?php

use Jalejandro\DecampoacampoChallenge\Http\EliminarProductoController;
use Jalejandro\DecampoacampoChallenge\Http\ListarProductosController;
use Jalejandro\DecampoacampoChallenge\Http\MostrarProductoController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$mostrarProductoRoute = new Route('/productos/{id}')
    ->setMethods(['GET'])
    ->setRequirements(['id' => '\d+'])
    ->setDefaults(['_controller' => MostrarProductoController::class]);

$eliminarProductoRoute = new Route('/productos/{id}')
    ->setMethods(['DELETE'])
    ->setRequirements(['id' => '\d+'])
    ->setDefaults(['_controller' => EliminarProductoController::class]);

$listarProductosRoute = new Route('/productos')
    ->setMethods(['GET'])
    ->setDefaults(['_controller' => ListarProductosController::class]);

$routes = new RouteCollection();

$routes->add('mostrar_producto', $mostrarProductoRoute);
$routes->add('eliminar_producto', $eliminarProductoRoute);
$routes->add('listar_productos', $listarProductosRoute);

return $routes;
