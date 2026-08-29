<?php

namespace Jalejandro\DecampoacampoChallenge\Model;

interface ProductoRepository
{
    public function findById(int $id): ?Producto;
    public function findAll(): array;
}