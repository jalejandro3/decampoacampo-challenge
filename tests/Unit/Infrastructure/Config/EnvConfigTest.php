<?php

namespace Tests\Unit\Infrastructure\Config;

use Jalejandro\DecampoacampoChallenge\Exception\ConfiguracionException;
use Jalejandro\DecampoacampoChallenge\Infrastructure\EnvConfig;
use PHPUnit\Framework\TestCase;

class EnvConfigTest extends TestCase
{
    public function test_env_config_con_precio_usd_null_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('PRECIO_USD no está configurado.');
        new EnvConfig(['PRECIO_USD' => null]);
    }

    public function test_env_config_con_precio_usd_no_numerico_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('PRECIO_USD debe tener valor numérico.');
        new EnvConfig(['PRECIO_USD' => 'hola']);
    }

    public function test_env_config_con_precio_usd_negativo_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('PRECIO_USD debe ser mayor a 0.');
        new EnvConfig(['PRECIO_USD' => '-1000']);
    }

    public function test_env_config_con_precio_usd_cero_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('PRECIO_USD debe ser mayor a 0.');
        new EnvConfig(['PRECIO_USD' => '0.0']);
    }

    public function test_env_config_con_precio_usd_correcto_crea_configuration_correcta()
    {
        $precioUsd = '1000.0';
        $envConfig = new EnvConfig(['PRECIO_USD' => $precioUsd]);
        $envConfigPrecioUsd = $envConfig->getPrecioUsd();

        $this->assertIsFloat($envConfigPrecioUsd);
        $this->assertSame((float) $precioUsd, $envConfigPrecioUsd);
    }
}
