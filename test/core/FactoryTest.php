<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Factory;

class FactoryTest extends TestCase
{

    const DEFAULT_OPTIONS = [
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION
    ];


    public function testInit()
    {
        $this->assertInstanceOf('\Wolff\Core\Http\Response', Factory::response());
        $this->assertInstanceOf('\Wolff\Core\Http\Request', Factory::request());
        $this->assertInstanceOf('\Wolff\Core\Query', Factory::query(new \PDOStatement));
        $this->assertInstanceOf('\Wolff\Core\Controller', Factory::controller());
        $this->assertNull(Factory::connection([], []));

        $this->expectException(\PDOException::class);

        Factory::connection([
            'name' => 'wolff',
            'port' => 8080,
        ], self::DEFAULT_OPTIONS);
    }
}
