<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\المشترياتController;
use App\Repository\المشترياتRepository;
use App\Entity\المشتريات;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testالمشتريات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(المشترياتRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new المشترياتController($this->repository, $this->entityManager);
    }

    public function testGetAll(): void
    {
        $expectedResponse = [
            ['id' => 1, 'name' => 'Test'],
            ['id' => 2, 'name' => 'Test2'],
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testGetOne(): void
    {
        $expectedResponse = ['id' => 1, 'name' => 'Test'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse);

        $response = $this->controller->getOne(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testGetOneNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getOne(1);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test'];
        $expectedResponse = ['id' => 1, 'name' => 'Test'];

        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse);

        $response = $this->controller->create($data);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testUpdate(): void
    {
        $data = ['name' => 'Test'];
        $expectedResponse = ['id' => 1, 'name' => 'Test'];

        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, $data)
            ->willReturn($expectedResponse);

        $response = $this->controller->update(1, $data);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testDelete(): void
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->controller->delete(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\المشترياتController.php

namespace App\Controller;

use App\Repository\المشترياتRepository;
use App\Entity\المشتريات;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class المشترياتController
{
    private $repository;
    private $entityManager;

    public function __construct(المشترياتRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getAll(): Response
    {
        $items = $this->repository->findAll();
        return new Response(json_encode($items));
    }

    public function getOne(int $id): Response
    {
        $item = $this->repository->find($id);
        if (!$item) {
            throw new NotFoundHttpException('Item not found');
        }
        return new Response(json_encode($item));
    }

    public function create(array $data): Response
    {
        $item = $this->repository->create($data);
        $this->entityManager->persist($item);
        $this->entityManager->flush();
        return new Response(json_encode($item), Response::HTTP_CREATED);
    }

    public function update(int $id, array $data): Response
    {
        $item = $this->repository->update($id, $data);
        $this->entityManager->flush();
        return new Response(json_encode($item));
    }

    public function delete(int $id): Response
    {
        $this->repository->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}