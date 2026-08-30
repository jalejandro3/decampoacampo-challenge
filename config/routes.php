<?php

use Jalejandro\DecampoacampoChallenge\Http\ActualizarProductoController;
use Jalejandro\DecampoacampoChallenge\Http\CrearProductoController;
use Jalejandro\DecampoacampoChallenge\Http\EliminarProductoController;
use Jalejandro\DecampoacampoChallenge\Http\ListarProductosController;
use Jalejandro\DecampoacampoChallenge\Http\MostrarProductoController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$mostrarProductoRoute = new Route('/productos/{id}')
    ->setMethods(['GET'])
    ->setRequirements(['id' => '\d+'])
    ->setDefaults(['_controller' => MostrarProductoController::class]);

$actualizarProductoRoute = new Route('/productos/{id}')
    ->setMethods(['PUT'])
    ->setRequirements(['id' => '\d+'])
    ->setDefaults(['_controller' => ActualizarProductoController::class]);

$eliminarProductoRoute = new Route('/productos/{id}')
    ->setMethods(['DELETE'])
    ->setRequirements(['id' => '\d+'])
    ->setDefaults(['_controller' => EliminarProductoController::class]);

$listarProductosRoute = new Route('/productos')
    ->setMethods(['GET'])
    ->setDefaults(['_controller' => ListarProductosController::class]);

$crearProductoRoute = new Route('/productos')
    ->setMethods(['POST'])
    ->setDefaults(['_controller' => CrearProductoController::class]);

$routes = new RouteCollection();

$routes->add('mostrar_producto', $mostrarProductoRoute);
$routes->add('actualizar_producto', $actualizarProductoRoute);
$routes->add('eliminar_producto', $eliminarProductoRoute);
$routes->add('listar_productos', $listarProductosRoute);
$routes->add('crear_producto', $crearProductoRoute);

return $routes;
