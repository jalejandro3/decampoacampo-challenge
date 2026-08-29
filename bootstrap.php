<?php

use Dotenv\Dotenv;
use Jalejandro\DecampoacampoChallenge\Application\ListarProductos;
use Jalejandro\DecampoacampoChallenge\Application\MostrarProducto;
use Jalejandro\DecampoacampoChallenge\Http\ListarProductosController;
use Jalejandro\DecampoacampoChallenge\Http\MostrarProductoController;
use Jalejandro\DecampoacampoChallenge\Infrastructure\EnvConfig;
use Jalejandro\DecampoacampoChallenge\Infrastructure\PDOProductoRepository;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = new EnvConfig([
    'PRECIO_USD' => $_ENV['PRECIO_USD'] ?? null,
    'DB_HOST' => $_ENV['DB_HOST'] ?? null,
    'DB_NAME' => $_ENV['DB_NAME'] ?? null,
    'DB_USER' => $_ENV['DB_USER'] ?? null,
    'DB_PASS' => $_ENV['DB_PASS'] ?? '',
    'DB_PORT' => $_ENV['DB_PORT'] ?? null,
]);

$dsn = "mysql:host={$config->getDbHost()};port={$config->getDbPort()};dbname={$config->getDbName()};charset=utf8mb4";
$pdo = new PDO($dsn, $config->getDbUser(), $config->getDbPass(), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$repositorio = new PDOProductoRepository($pdo);
$mostrarProducto = new MostrarProducto($config, $repositorio);
$listarProductos = new ListarProductos($config, $repositorio);

$controllers = [
    MostrarProductoController::class => new MostrarProductoController($mostrarProducto),
    ListarProductosController::class => new ListarProductosController($listarProductos),
];

return $controllers;
