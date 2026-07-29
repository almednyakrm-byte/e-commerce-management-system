<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Panther\PantherTestCase;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Controller\ProductController;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\NonUniqueResultException;

class Testالمنتجات extends PantherTestCase
{
    private $client;
    private $entityManager;
    private $productRepository;
    private $productController;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->productController = new ProductController($this->entityManager, $this->productRepository);
    }

    public function testGetProducts(): void
    {
        $this->productRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Product(1, 'Product 1'), new Product(2, 'Product 2')]);

        $response = $this->client->request('GET', '/api/products');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testGetProduct(): void
    {
        $this->productRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Product(1, 'Product 1'));

        $response = $this->client->request('GET', '/api/products/1');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testGetProductNotFound(): void
    {
        $this->productRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $this->client->request('GET', '/api/products/1');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testCreateProduct(): void
    {
        $product = new Product(1, 'Product 1');
        $this->productRepository->expects($this->once())
            ->method('save')
            ->with($product);

        $response = $this->client->request('POST', '/api/products', ['json' => ['name' => 'Product 1']]);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testCreateProductValidation(): void
    {
        $response = $this->client->request('POST', '/api/products', ['json' => ['name' => '']]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUpdateProduct(): void
    {
        $product = new Product(1, 'Product 1');
        $this->productRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($product);
        $this->productRepository->expects($this->once())
            ->method('save')
            ->with($product);

        $response = $this->client->request('PUT', '/api/products/1', ['json' => ['name' => 'Product 2']]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testUpdateProductNotFound(): void
    {
        $this->productRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $this->client->request('PUT', '/api/products/1', ['json' => ['name' => 'Product 2']]);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testDeleteProduct(): void
    {
        $product = new Product(1, 'Product 1');
        $this->productRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($product);
        $this->productRepository->expects($this->once())
            ->method('remove')
            ->with($product);

        $response = $this->client->request('DELETE', '/api/products/1');
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteProductNotFound(): void
    {
        $this->productRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $this->client->request('DELETE', '/api/products/1');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}