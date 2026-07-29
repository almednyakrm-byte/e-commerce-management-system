<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Auth;
use App\Database;

class TestAuth extends TestCase
{
    private $auth;
    private $database;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
        $this->auth = new Auth($this->database);
    }

    public function testLoginSuccess()
    {
        $this->database->method('getUser')
            ->with('test@example.com', 'password123')
            ->willReturn(['id' => 1, 'email' => 'test@example.com']);

        $result = $this->auth->login('test@example.com', 'password123');
        $this->assertTrue($result);
        $this->assertEquals($_SESSION['user_id'], 1);
    }

    public function testLoginFailure()
    {
        $this->database->method('getUser')
            ->with('test@example.com', 'wrongpassword')
            ->willReturn(null);

        $result = $this->auth->login('test@example.com', 'wrongpassword');
        $this->assertFalse($result);
        $this->assertNull($_SESSION['user_id']);
    }

    public function testRegisterSuccess()
    {
        $this->database->method('insertUser')
            ->with('test@example.com', 'password123')
            ->willReturn(1);

        $result = $this->auth->register('test@example.com', 'password123');
        $this->assertTrue($result);
        $this->assertEquals($_SESSION['user_id'], 1);
    }

    public function testRegisterFailure()
    {
        $this->database->method('insertUser')
            ->with('test@example.com', 'password123')
            ->willReturn(0);

        $result = $this->auth->register('test@example.com', 'password123');
        $this->assertFalse($result);
        $this->assertNull($_SESSION['user_id']);
    }
}