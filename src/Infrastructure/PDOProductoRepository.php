<?php

namespace Jalejandro\DecampoacampoChallenge\Infrastructure;

use Jalejandro\DecampoacampoChallenge\Model\Producto;
use Jalejandro\DecampoacampoChallenge\Model\ProductoRepository;
use PDO;

class PDOProductoRepository implements ProductoRepository
{
    public function __construct(private PDO $pdo) {}

    public function findById(int $id): ?Producto
    {
        $stmt = $this->pdo->prepare('SELECT nombre, descripcion, precio FROM productos WHERE id = :id');
        $stmt->execute([':id' => $id]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product === false) {
            return null;
        }

        return new Producto($product['nombre'], $product['descripcion'], (float) $product['precio']);
    }
}
