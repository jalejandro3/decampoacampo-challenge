<?php

namespace Jalejandro\DecampoacampoChallenge\Infrastructure;

use Jalejandro\DecampoacampoChallenge\Application\Configuracion;
use Jalejandro\DecampoacampoChallenge\Exception\ConfiguracionException;

readonly class EnvConfig implements Configuracion
{
    private float $precioUsd;
    private string $dbHost;
    private string $dbUser;
    private string $dbName;
    private string $dbPass;
    private int $dbPort;

    /**
     * @throws ConfiguracionException
     */
    public function __construct(array $config)
    {
        $this->precioUsd = $this->validarPrecioUsd($config);
        $this->dbHost = $this->validarDbHost($config);
        $this->dbUser = $this->validarDbUser($config);
        $this->dbName = $this->validarDbName($config);
        $this->dbPort = $this->validarDbPort($config);
        $this->dbPass = $config['DB_PASS'] ?? '';
    }

    public function getPrecioUsd(): float
    {
        return $this->precioUsd;
    }

    public function getDbHost(): string
    {
        return $this->dbHost;
    }

    public function getDbUser(): string
    {
        return $this->dbUser;
    }

    public function getDbName(): string
    {
        return $this->dbName;
    }

    public function getDbPort(): int
    {
        return $this->dbPort;
    }

    public function getDbPass(): string
    {
        return $this->dbPass;
    }

    /**
     * @throws ConfiguracionException
     */
    private function validarPrecioUsd(array $config): float
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

        return $precioUsd;
    }

    /**
     * @throws ConfiguracionException
     */
    private function validarDbHost(array $config): string
    {
        if (!isset($config['DB_HOST'])) {
            throw new ConfiguracionException('DB_HOST no está configurado.');
        }

        return $config['DB_HOST'];
    }

    /**
     * @throws ConfiguracionException
     */
    private function validarDbUser(array $config): string
    {
        if (!isset($config['DB_USER'])) {
            throw new ConfiguracionException('DB_USER no está configurado.');
        }

        return $config['DB_USER'];
    }

    /**
     * @throws ConfiguracionException
     */
    private function validarDbName(array $config): string
    {
        if (!isset($config['DB_NAME'])) {
            throw new ConfiguracionException('DB_NAME no está configurado.');
        }

        return $config['DB_NAME'];
    }

    /**
     * @throws ConfiguracionException
     */
    private function validarDbPort(array $config): int
    {
        if (!isset($config['DB_PORT'])) {
            throw new ConfiguracionException('DB_PORT no está configurado.');
        }

        if (!is_numeric($config['DB_PORT'])) {
            throw new ConfiguracionException('DB_PORT debe tener valor numérico.');
        }

        return (int) $config['DB_PORT'];
    }
}
