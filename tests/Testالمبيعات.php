<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\المبيعات;
use App\Repositories\المبيعاتRepository;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testالمبيعات extends TestCase
{
    private MockObject $pdo;
    private MockObject $stmt;
    private المبيعاتRepository $repository;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->repository = new المبيعاتRepository($this->pdo);
    }

    public function testGetAll(): void
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM المبيعات')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Test'],
                ['id' => 2, 'name' => 'Test2'],
            ]);

        $result = $this->repository->getAll();
        $this->assertCount(2, $result);
    }

    public function testGetById(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM المبيعات WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Test']);

        $result = $this->repository->getById(1);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreate(): void
    {
        $request = new Request(['name' => 'Test']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO المبيعات (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'Test');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->repository->create($request);
        $this->assertTrue($result);
    }

    public function testUpdate(): void
    {
        $request = new Request(['name' => 'Test2']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE المبيعات SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'Test2');

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->repository->update(1, $request);
        $this->assertTrue($result);
    }

    public function testDelete(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM المبيعات WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->repository->delete(1);
        $this->assertTrue($result);
    }
}