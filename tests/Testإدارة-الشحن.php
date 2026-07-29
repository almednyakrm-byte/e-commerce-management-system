<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Controller\ShipmentController;
use App\Repository\ShipmentRepository;
use App\Entity\Shipment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Testإدارة-الشحن extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $tokenStorage;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ShipmentRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->controller = new ShipmentController($this->repository, $this->entityManager, $this->tokenStorage);
    }

    public function testGetAllShipments()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->createMock(Shipment::class)]);

        $response = $this->controller->getAllShipments();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetShipmentById()
    {
        $shipment = $this->createMock(Shipment::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($shipment);

        $response = $this->controller->getShipmentById(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateShipment()
    {
        $shipment = $this->createMock(Shipment::class);
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($shipment);
        $this->entityManager->expects($this->once())
            ->method('flush');

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(['name' => 'shipment']);

        $response = $this->controller->createShipment($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateShipment()
    {
        $shipment = $this->createMock(Shipment::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($shipment);
        $this->entityManager->expects($this->once())
            ->method('flush');

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(['name' => 'shipment']);

        $response = $this->controller->updateShipment(1, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteShipment()
    {
        $shipment = $this->createMock(Shipment::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($shipment);
        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($shipment);
        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->deleteShipment(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1. `testGetAllShipments`: Verifies that the `getAllShipments` method returns a JSON response with a 200 status code when the `findAll` method of the `ShipmentRepository` is called.
2. `testGetShipmentById`: Verifies that the `getShipmentById` method returns a JSON response with a 200 status code when the `find` method of the `ShipmentRepository` is called.
3. `testCreateShipment`: Verifies that the `createShipment` method creates a new shipment and returns a JSON response with a 201 status code when the `persist` and `flush` methods of the `EntityManager` are called.
4. `testUpdateShipment`: Verifies that the `updateShipment` method updates an existing shipment and returns a JSON response with a 200 status code when the `find` and `flush` methods of the `EntityManager` are called.
5. `testDeleteShipment`: Verifies that the `deleteShipment` method deletes an existing shipment and returns a JSON response with a 204 status code when the `remove` and `flush` methods of the `EntityManager` are called.

Note that this test file uses mocking to isolate the dependencies of the `ShipmentController` and to focus on the behavior of the controller itself.