<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Utils\Auth;
use Wolff\Exception\InvalidArgumentException;

class AuthTest extends TestCase
{

    private $auth;


    public function setUp(): void
    {
        global $argv;
        if (!isset($argv[1]) || $argv[1] !== '-db') {
            $this->markTestSkipped('Skipped auth test!');
        }

        $this->auth = new Auth([ 'dsn' => 'sqlite::memory:' ], [
            'cost' => '15'
        ]);
        $this->auth->query('CREATE TABLE customer
            (customer_id INT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL)');
        $this->auth->setTable('customer');
        $this->auth->setUnique('email');
    }


    public function testInit()
    {
        $this->assertEquals([
            'cost' => '15',
        ], $this->auth->getOptions());
        $this->auth->setOptions([
            'cost' => '9',
        ]);
        $this->assertEquals([
            'cost' => '9',
        ], $this->auth->getOptions());
        $this->assertNull($this->auth->getId());
        $this->assertTrue($this->auth->register([
            'customer_id'      => '1',
            'name'             => 'alejandro',
            'email'            => 'alejandro@hotmail.com',
            'password'         => 'canislupus',
            'password_confirm' => 'canislupus',
        ]));
        $this->assertFalse($this->auth->register([
            'customer_id'      => '2',
            'name'             => 'same email',
            'email'            => 'alejandro@hotmail.com',
            'password'         => '12345',
            'password_confirm' => '12345',
        ]));
        $this->assertFalse($this->auth->register([
            'customer_id'      => '3',
            'name'             => 'wolff',
            'email'            => 'contact@getwolff.com',
            'password'         => 'canislupus',
            'password_confirm' => 'anotherpassword',
        ]));
        $this->assertEquals(1, $this->auth->getLastId());
        $this->assertNull($this->auth->getUser());
        $this->assertTrue($this->auth->login([
            'email'    => 'alejandro@hotmail.com',
            'password' => 'canislupus',
        ]));
        $this->assertEquals('alejandro', $this->auth->getUser()['name']);
        $this->assertEquals('alejandro@hotmail.com', $this->auth->getUser()['email']);
        $this->expectException(InvalidArgumentException::class);
        $this->auth->login([
            'email' => '123@hotmail.com',
        ]);
        $this->auth->register([]);
    }
}
