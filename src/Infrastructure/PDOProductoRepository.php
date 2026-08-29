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

        return $this->mapearProducto($product);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare('SELECT id, nombre, descripcion, precio FROM productos ORDER BY id ASC');
        $stmt->execute();

        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($producto) => $this->mapearProducto($producto), $productos);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM productos WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }

    private function mapearProducto(array $producto): Producto
    {
        return new Producto($producto['nombre'], $producto['descripcion'], (float) $producto['precio']);
    }
}
