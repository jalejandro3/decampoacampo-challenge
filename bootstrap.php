<?php

use Dotenv\Dotenv;
use Jalejandro\DecampoacampoChallenge\Application\EliminarProducto;
use Jalejandro\DecampoacampoChallenge\Application\ListarProductos;
use Jalejandro\DecampoacampoChallenge\Application\MostrarProducto;
use Jalejandro\DecampoacampoChallenge\Http\EliminarProductoController;
use Jalejandro\DecampoacampoChallenge\Http\ListarProductosController;
use Jalejandro\DecampoacampoChallenge\Http\MostrarProductoController;
use Jalejandro\DecampoacampoChallenge\Infrastructure\EnvConfig;
use Jalejandro\DecampoacampoChallenge\Infrastructure\PDOProductoRepository;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$config = new EnvConfig([
    'PRECIO_USD' => $_SERVER['PRECIO_USD'] ?? (getenv('PRECIO_USD') ?: null),
    'DB_HOST' => $_SERVER['DB_HOST'] ?? (getenv('DB_HOST') ?: null),
    'DB_NAME' => $_SERVER['DB_NAME'] ?? (getenv('DB_NAME') ?: null),
    'DB_USER' => $_SERVER['DB_USER'] ?? (getenv('DB_USER') ?: null),
    'DB_PASS' => $_SERVER['DB_PASS'] ?? (getenv('DB_PASS') ?: ''),
    'DB_PORT' => $_SERVER['DB_PORT'] ?? (getenv('DB_PORT') ?: null),
]);

$dsn = "mysql:host={$config->getDbHost()};port={$config->getDbPort()};dbname={$config->getDbName()};charset=utf8mb4";
$pdo = new PDO($dsn, $config->getDbUser(), $config->getDbPass(), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$repositorio = new PDOProductoRepository($pdo);
$mostrarProducto = new MostrarProducto($config, $repositorio);
$listarProductos = new ListarProductos($config, $repositorio);
$eliminarProducto = new EliminarProducto($repositorio);

$controllers = [
    MostrarProductoController::class => new MostrarProductoController($mostrarProducto),
    EliminarProductoController::class => new EliminarProductoController($eliminarProducto),
    ListarProductosController::class => new ListarProductosController($listarProductos),
];

return $controllers;
