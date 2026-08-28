<?php

namespace Integration\Infrastructure;

use Jalejandro\DecampoacampoChallenge\Infrastructure\PDOProductoRepository;
use PHPUnit\Framework\TestCase;
use PDO;

class PDOProductoRepositoryTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $dsn = "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    protected function tearDown(): void
    {
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $this->pdo->exec('TRUNCATE TABLE productos');
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        parent::tearDown();
    }

    public function test_pdo_producto_repository_con_producto_no_existente_retorna_null()
    {
        $pdoProductoRepository = new PDOProductoRepository($this->pdo);
        $producto = $pdoProductoRepository->findById(1);

        $this->assertNull($producto);
    }

    public function test_pdo_producto_repository_con_producto_existente_retorna_producto()
    {
        $stmt = $this->pdo->prepare('INSERT INTO productos (nombre, descripcion, precio) VALUES (:nombre, :descripcion, :precio)');
        $stmt->execute([
            ':nombre' => 'Ganado',
            ':descripcion' => 'Maute',
            ':precio' => 1000.0,
        ]);

        $pdoProductoRepository = new PDOProductoRepository($this->pdo);
        $producto = $pdoProductoRepository->findById((int) $this->pdo->lastInsertId());
        $productoArray = $producto->toArray();

        $this->assertNotNull($producto);
        $this->assertArrayHasKey('nombre', $productoArray);
        $this->assertArrayHasKey('descripcion', $productoArray);
        $this->assertArrayHasKey('precio', $productoArray);

        $this->assertEquals('Ganado', $productoArray['nombre']);
        $this->assertEquals('Maute', $productoArray['descripcion']);
        $this->assertSame(1000.0, $productoArray['precio']);
    }
}