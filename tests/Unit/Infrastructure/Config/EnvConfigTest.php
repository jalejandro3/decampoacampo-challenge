<?php

namespace Tests\Unit\Infrastructure\Config;

use Jalejandro\DecampoacampoChallenge\Exception\ConfiguracionException;
use Tests\ObjectMother\EnvConfigMother;
use PHPUnit\Framework\TestCase;

class EnvConfigTest extends TestCase
{
    public function test_env_config_con_precio_usd_null_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('PRECIO_USD no está configurado.');
        EnvConfigMother::create(['PRECIO_USD' => null]);
    }

    public function test_env_config_con_precio_usd_no_numerico_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('PRECIO_USD debe tener valor numérico.');
        EnvConfigMother::create(['PRECIO_USD' => 'hola']);
    }

    public function test_env_config_con_precio_usd_negativo_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('PRECIO_USD debe ser mayor a 0.');
        EnvConfigMother::create(['PRECIO_USD' => '-1000']);
    }

    public function test_env_config_con_precio_usd_cero_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('PRECIO_USD debe ser mayor a 0.');
        EnvConfigMother::create(['PRECIO_USD' => '0.0']);
    }

    public function test_env_config_con_precio_usd_correcto_crea_configuration_correcta()
    {
        $precioUsd = '1000.0';
        $envConfig = EnvConfigMother::create(['PRECIO_USD' => $precioUsd]);
        $envConfigPrecioUsd = $envConfig->getPrecioUsd();

        $this->assertIsFloat($envConfigPrecioUsd);
        $this->assertSame((float) $precioUsd, $envConfigPrecioUsd);
    }

    public function test_env_config_con_db_host_null_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('DB_HOST no está configurado.');
        EnvConfigMother::create(['DB_HOST' => null]);
    }

    public function test_env_config_con_db_host_correcto_crea_configuration_correcta()
    {
        $dbHost = '127.0.0.1';
        $envConfig = EnvConfigMother::create(['DB_HOST' => $dbHost]);

        $this->assertSame($dbHost, $envConfig->getDbHost());
    }

    public function test_env_config_con_db_user_null_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('DB_USER no está configurado.');
        EnvConfigMother::create(['DB_USER' => null]);
    }

    public function test_env_config_con_db_user_correcto_crea_configuration_correcta()
    {
        $dbUser = 'root';
        $envConfig = EnvConfigMother::create(['DB_USER' => $dbUser]);

        $this->assertSame($dbUser, $envConfig->getDbUser());
    }

    public function test_env_config_con_db_port_null_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('DB_PORT no está configurado.');
        EnvConfigMother::create(['DB_PORT' => null]);
    }

    public function test_env_config_con_db_port_no_numerico_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('DB_PORT debe tener valor numérico.');
        EnvConfigMother::create(['DB_PORT' => 'wxyz']);
    }

    public function test_env_config_con_db_port_correcto_crea_configuration_correcta()
    {
        $dbPort = '3306';
        $envConfig = EnvConfigMother::create(['DB_PORT' => $dbPort]);

        $this->assertSame((int) $dbPort, $envConfig->getDbPort());
    }

    public function test_env_config_con_db_name_null_arroja_configuracion_exception()
    {
        $this->expectException(ConfiguracionException::class);
        $this->expectExceptionMessageIs('DB_NAME no está configurado.');
        EnvConfigMother::create(['DB_NAME' => null]);
    }

    public function test_env_config_con_db_name_correcto_crea_configuration_correcta()
    {
        $dbName = 'decampoacampo_challenge';
        $envConfig = EnvConfigMother::create(['DB_NAME' => $dbName]);

        $this->assertSame($dbName, $envConfig->getDbName());
    }

    public function test_env_config_con_db_pass_null_retorna_vacio()
    {
        $envConfig = EnvConfigMother::create(['DB_PASS' => null]);
        $this->assertSame('', $envConfig->getDbPass());
    }

    public function test_env_config_con_db_pass_vacio_crea_configuration_correcta()
    {
        $dbPass = '';
        $envConfig = EnvConfigMother::create(['DB_PASS' => $dbPass]);

        $this->assertSame($dbPass, $envConfig->getDbPass());
    }

    public function test_env_config_con_db_pass_correcto_crea_configuration_correcta()
    {
        $dbPass = 'password';
        $envConfig = EnvConfigMother::create(['DB_PASS' => $dbPass]);

        $this->assertSame($dbPass, $envConfig->getDbPass());
    }
}
