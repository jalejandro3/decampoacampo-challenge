<?php

require __DIR__ . '/../vendor/autoload.php';

$mostrarProducto = require __DIR__ . '/../bootstrap.php';

preg_match('#/productos/(\d+)#', $_SERVER['REQUEST_URI'], $matches);

$resultado = $mostrarProducto->execute((int) $matches[1]);

header('Content-Type: application/json');
echo json_encode($resultado);