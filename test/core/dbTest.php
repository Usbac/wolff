<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\DB;
use Wolff\Core\Query;
use PDO;

class DBTest extends TestCase
{

    const DSN = '%s:host=%s; dbname=%s';
    const DEFAULT_ENCODING = 'set names utf8mb4 collate utf8mb4_unicode_ci';
    const OPTIONS = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION
    ];
    const EXPECTED = [
        [
            'user_id'  => '1',
            'name'     => 'Alejandro',
            'email'    => 'contact@getwolff.com',
            'password' => '123456'
        ],
        [
            'user_id'  => '2',
            'name'     => 'Bruce Ismay',
            'email'    => 'bruce@wsl.com',
            'password' => '123'
        ],
        [
            'user_id'  => '3',
            'name'     => 'Margaret Brown',
            'email'    => 'margaret@wsl.com',
            'password' => '321'
        ],
        [
            'user_id'  => '4',
            'name'     => 'Thomas Andrews',
            'email'    => 'thomas@wsl.com',
            'password' => 'harlandandwolff'
        ],
    ];

    private $connection;


    public function setUp(): void
    {
        global $argv;
        if (!isset($argv[1]) || $argv[1] !== '-db') {
            $this->markTestSkipped('Skipped database test!');
        }

        $dsn = sprintf(self::DSN, DBMS, SERVER, DB);

        try {
            $this->connection = new PDO($dsn, USERNAME, PASSWORD, self::OPTIONS);
            $this->connection->prepare(self::DEFAULT_ENCODING)->execute();
        } catch (\PDOException $err) {
            throw $err;
        }

        $this->createSchema();
    }


    private function createSchema()
    {
        $this->resetDB();
        $this->connection->prepare("CREATE TABLE IF NOT EXISTS `user` (
            `user_id` INT AUTO_INCREMENT NOT NULL,
            `name` VARCHAR(255) NOT NULL DEFAULT '',
            `email` VARCHAR(255) NOT NULL DEFAULT '',
            `password` VARCHAR(255) NOT NULL DEFAULT '',
            PRIMARY KEY (`user_id`))")->execute();

        $this->connection->prepare("INSERT INTO `user` (name, email, password) VALUES
            (?, ?, ?), (?, ?, ?), (?, ?, ?), (?, ?, ?)")->execute($this->getDbValues());
    }


    private function getDbValues()
    {
        $values = [];

        foreach (self::EXPECTED as $user) {
            foreach ($user as $key => $val) {
                if ($key !== 'user_id') {
                    array_push($values, $val);
                }
            }
        }

        return $values;
    }


    public function testInit()
    {
        $db = new DB([
            'dbms'     => DBMS,
            'server'   => SERVER,
            'name'     => DB,
            'username' => USERNAME,
            'password' => PASSWORD
        ]);

        $this->assertInstanceOf(Query::class, $db->query('SELECT * FROM `user`'));
        $this->assertArrayEquals(self::EXPECTED, $db->query('SELECT * FROM `user`')->get());
        $this->assertArrayEquals(self::EXPECTED, $db->select('user'));
        $this->assertArrayEquals(array_slice(self::EXPECTED, 0, 2), $db->query('SELECT * FROM `user`')->limit(0, 2));
        $this->assertArrayEquals(self::EXPECTED[1], $db->query('SELECT * FROM `user` WHERE email LIKE ?', '%@wsl.com')->first());
        $this->assertEquals('Alejandro', $db->query('SELECT * FROM `user`')->first('name'));
        $this->assertEquals(4, $db->query('SELECT * FROM `user`')->count());
        $this->assertEquals(4, $db->count('user'));
        $this->assertArrayEquals([
            'Alejandro', 'Bruce Ismay', 'Margaret Brown', 'Thomas Andrews'
        ], $db->query('SELECT * FROM `user`')->pick('name'));

        $this->assertInstanceOf(\PDO::class, $db->getPdo());
        $this->assertTrue($db->tableExists('user'));
        $this->assertFalse($db->tableExists('another_table'));
        $this->assertTrue($db->columnExists('user', 'name'));
        $this->assertFalse($db->columnExists('user', 'another'));
        $this->assertFalse($db->columnExists('another', 'another'));
        $this->assertTrue($db->delete('user', 'name = ?', 'Alejandro'));
    }


    private function assertArrayEquals(array $a, array $b)
    {
        foreach ($b as $key => $val) {
            if (!isset($a[$key]) || $val !== $a[$key]) {
                $this->assertTrue(false);
            }
        }

        $this->assertTrue(true);
    }


    private function resetDB()
    {
        $this->connection->prepare('DROP DATABASE ' . DB . ';
            CREATE DATABASE ' . DB . ';
            use ' . DB)->execute();
    }


    public function tearDown(): void
    {
        $this->resetDB();
    }
}
