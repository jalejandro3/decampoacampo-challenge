<?php

namespace Jalejandro\DecampoacampoChallenge\Infrastructure;

use Jalejandro\DecampoacampoChallenge\Exception\ConfiguracionException;

readonly class EnvConfig
{
    private float $precioUsd;

    /**
     * @throws ConfiguracionException
     */
    public function __construct(array $config)
    {
        if (!isset($config['PRECIO_USD'])) {
            throw new ConfiguracionException('PRECIO_USD no está configurado.');
        }

        if (!is_numeric($config['PRECIO_USD'])) {
            throw new ConfiguracionException('PRECIO_USD debe tener valor numérico.');
        }

        $precioUsd = (float) $config['PRECIO_USD'];

        if ($precioUsd <= 0.0) {
            throw new ConfiguracionException('PRECIO_USD debe ser mayor a 0.');
        }

        $this->precioUsd = $precioUsd;
    }

    public function getPrecioUsd(): float
    {
        return $this->precioUsd;
    }
}
