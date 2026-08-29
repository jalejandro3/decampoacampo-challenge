<?php

namespace Integration\Infrastructure;

use Jalejandro\DecampoacampoChallenge\Infrastructure\PDOProductoRepository;
use Jalejandro\DecampoacampoChallenge\Model\Producto;
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
        $this->insertarProducto('Ganado', 'Maute', 1000.0);

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

    public function test_pdo_producto_repository_con_productos_no_existentes_retorna_una_array_vacio()
    {
        $pdoProductoRepository = new PDOProductoRepository($this->pdo);
        $productos = $pdoProductoRepository->findAll();

        $this->assertIsArray($productos);
        $this->assertEmpty($productos);
    }

    public function test_pdo_producto_repository_con_productos_existentes_retorna_una_array_de_productos()
    {
        $this->insertarProducto('Ganado', 'Maute', 100000.0);
        $this->insertarProducto('Cerdo', 'Lechon', 50000.0);

        $pdoProductoRepository = new PDOProductoRepository($this->pdo);
        $productos = $pdoProductoRepository->findAll();

        $this->assertIsArray($productos);
        $this->assertCount(2, $productos);
        $this->assertContainsOnlyInstancesOf(Producto::class, $productos);

        $productoArray = $productos[0]->toArray();

        $this->assertEquals('Ganado', $productoArray['nombre']);
    }

    public function test_pdo_producto_repository_eliminar_producto_no_existente_retorna_falso()
    {
        $pdoProductoRepository = new PDOProductoRepository($this->pdo);
        $result = $pdoProductoRepository->delete(2);

        $this->assertFalse( $result);
    }

    public function test_pdo_producto_repository_eliminar_producto_existente()
    {
        $this->insertarProducto('Ganado', 'Maute', 1000.0);
        $productoId = (int) $this->pdo->lastInsertId();

        $pdoProductoRepository = new PDOProductoRepository($this->pdo);
        $result = $pdoProductoRepository->delete($productoId);

        $this->assertTrue($result);
        $this->assertNull($pdoProductoRepository->findById($productoId));
    }

    private function insertarProducto(string $nombre, string $descripcion, float $precio): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO productos (nombre, descripcion, precio) VALUES (:nombre, :descripcion, :precio)');
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
        ]);
    }
}