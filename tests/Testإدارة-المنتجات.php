<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProduitsController;
use App\Repository\ProduitsRepository;
use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Testإدارةالمنتجات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;
    private $requestStack;
    private $session;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProduitsRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->session = $this->createMock(SessionInterface::class);

        $this->controller = new ProduitsController(
            $this->repository,
            $this->entityManager,
            $this->router,
            $this->requestStack,
            $this->session
        );
    }

    public function testGetAllProduits(): void
    {
        $produits = [
            new Produits('Produit 1', 'Description 1'),
            new Produits('Produit 2', 'Description 2'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($produits);

        $response = $this->controller->getAllProduits();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($produits), $response->getContent());
    }

    public function testGetProduit(): void
    {
        $produit = new Produits('Produit 1', 'Description 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($produit);

        $response = $this->controller->getProduit(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($produit), $response->getContent());
    }

    public function testCreateProduit(): void
    {
        $produit = new Produits('Produit 1', 'Description 1');

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($produit);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $request = new Request();
        $request->request->set('nom', 'Produit 1');
        $request->request->set('description', 'Description 1');

        $response = $this->controller->createProduit($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($produit), $response->getContent());
    }

    public function testUpdateProduit(): void
    {
        $produit = new Produits('Produit 1', 'Description 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($produit);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $request = new Request();
        $request->request->set('nom', 'Produit 2');
        $request->request->set('description', 'Description 2');

        $response = $this->controller->updateProduit(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($produit), $response->getContent());
    }

    public function testDeleteProduit(): void
    {
        $produit = new Produits('Produit 1', 'Description 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($produit);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($produit);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->deleteProduit(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This test file assumes that the `ProduitsController` class has the following methods:

- `getAllProduits()`: Returns a response with a list of all products.
- `getProduit($id)`: Returns a response with a product by its ID.
- `createProduit(Request $request)`: Creates a new product and returns a response with the created product.
- `updateProduit($id, Request $request)`: Updates a product by its ID and returns a response with the updated product.
- `deleteProduit($id)`: Deletes a product by its ID and returns a response with a status code of 204 (No Content).

Also, this test file assumes that the `ProduitsRepository` class has the following methods:

- `findAll()`: Returns a list of all products.
- `find($id)`: Returns a product by its ID.

The test file uses the `MockObject` class from PHPUnit to mock the dependencies of the `ProduitsController` class. The `expects` method is used to specify the expected behavior of the mocked dependencies. The `willReturn` method is used to specify the return value of the mocked dependencies.