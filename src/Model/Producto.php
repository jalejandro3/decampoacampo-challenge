<?php

namespace Jalejandro\DecampoacampoChallenge\Model;

use Jalejandro\DecampoacampoChallenge\Exception\DatoInvalidoException;

readonly class Producto
{
    /**
     * @throws DatoInvalidoException
     */
    public function __construct(private ?int $id, private string $nombre, private string $descripcion, private float $precio)
    {
        if (trim($nombre) === '') {
            throw new DatoInvalidoException('El nombre del producto no puede estar vacío.');
        }

        if (trim($descripcion) === '') {
            throw new DatoInvalidoException('La descripción del producto no puede estar vacía.');
        }

        if ($precio <= 0) {
            throw new DatoInvalidoException('El precio del producto debe ser mayor a 0.');
        }
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function precioEnDolares(float $cotizacionDolar): float
    {
        return round($this->precio / $cotizacionDolar, 2);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
        ];
    }
}
