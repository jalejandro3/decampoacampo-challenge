<?php

namespace Jalejandro\DecampoacampoChallenge\Model;

interface ProductoRepository
{
    public function findById(int $id): ?Producto;
    public function findAll(): array;
    public function delete(int $id): bool;
    public function save(Producto $producto): int;
}
