<?php

namespace Jalejandro\DecampoacampoChallenge\Model;

use Jalejandro\DecampoacampoChallenge\Exception\PrecioInvalidoException;

readonly class Producto
{
    /**
     * @throws PrecioInvalidoException
     */
    public function __construct(private string $nombre, private string $descripcion, private float $precio)
    {
        if ($precio <= 0) {
            throw new PrecioInvalidoException('El precio del producto debe ser mayor a 0.');
        }
    }

    public function precioEnDolares(float $cotizacionDolar): float
    {
        return round($this->precio / $cotizacionDolar, 2);
    }

    public function toArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
        ];
    }
}