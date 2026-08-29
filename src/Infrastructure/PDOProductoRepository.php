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
        $stmt = $this->pdo->prepare('SELECT id, nombre, descripcion, precio FROM productos WHERE id = :id');
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

    public function save(Producto $producto): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO productos (nombre, descripcion, precio) VALUES (:nombre, :descripcion, :precio)');
        $stmt->execute([
            ':nombre' => $producto->getNombre(),
            ':descripcion' => $producto->getDescripcion(),
            ':precio' => $producto->getPrecio(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Producto $producto): void
    {
        $stmt = $this->pdo->prepare('UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio WHERE id = :id');
        $stmt->execute([
            ':id' => $producto->getId(),
            ':nombre' => $producto->getNombre(),
            ':descripcion' => $producto->getDescripcion(),
            ':precio' => $producto->getPrecio(),
        ]);
    }

    private function mapearProducto(array $producto): Producto
    {
        return new Producto((int) $producto['id'], $producto['nombre'], $producto['descripcion'], (float) $producto['precio']);
    }
}
