<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\StockManagementController;
use App\Repository\StockManagementRepository;
use App\Entity\StockManagement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Paginator\PaginatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Testإدارة-المخزون extends TestCase
{
    private $controller;
    private $repository;
    private $tokenStorage;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(StockManagementRepository::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->controller = new StockManagementController($this->repository, $this->tokenStorage);
    }

    public function testGetAll()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new StockManagement()]);

        $response = $this->controller->getAll($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new StockManagement());

        $response = $this->controller->getOne($this->request, [$id]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOneNotFound()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getOne($this->request, [$id]);
    }

    public function testCreate()
    {
        $data = ['name' => 'Test Stock'];
        $this->repository->expects($this->once())
            ->method('create')
            ->with(new StockManagement(), $data)
            ->willReturn(new StockManagement());

        $response = $this->controller->create($this->request, $data);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'Updated Stock'];
        $stock = new StockManagement();
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($stock);

        $this->repository->expects($this->once())
            ->method('update')
            ->with($stock, $data)
            ->willReturn($stock);

        $response = $this->controller->update($this->request, [$id], $data);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateNotFound()
    {
        $id = 1;
        $data = ['name' => 'Updated Stock'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->update($this->request, [$id], $data);
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new StockManagement());

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(new StockManagement());

        $response = $this->controller->delete($this->request, [$id]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->delete($this->request, [$id]);
    }
}


This test file covers the following scenarios:

1.  **GET ALL**: Tests the `getAll` method to retrieve all stock management records.
2.  **GET ONE**: Tests the `getOne` method to retrieve a single stock management record by ID.
3.  **GET ONE NOT FOUND**: Tests the `getOne` method when the requested ID does not exist.
4.  **CREATE**: Tests the `create` method to create a new stock management record.
5.  **UPDATE**: Tests the `update` method to update an existing stock management record.
6.  **UPDATE NOT FOUND**: Tests the `update` method when the requested ID does not exist.
7.  **DELETE**: Tests the `delete` method to delete a stock management record.
8.  **DELETE NOT FOUND**: Tests the `delete` method when the requested ID does not exist.

Each test method uses the `createMock` method to create mock objects for the `StockManagementRepository` and `TokenStorageInterface` dependencies. The `expects` method is used to specify the expected behavior of the mock objects. The `willReturn` method is used to specify the return value of the mock objects.