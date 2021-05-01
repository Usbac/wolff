<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Utils\Validation;

class ValidationTest extends TestCase
{

    private $validation;

    private $validation_2;


    public function setUp(): void
    {
        $_POST = [
            'name' => 'Alejandro',
            'age'  => 24
        ];

        $this->validation = new Validation();
        $this->validation->setData([
            'name'  => 'Thomas Andrews',
            'email' => 'thomas@wolff.com',
            'age'   => 39
        ]);
        $this->validation->setFields([
            'name' => [
                'minlen' => 10,
                'type'   => 'alpha'
            ],
            'email' => [
                'type'   => 'email'
            ],
            'age' => [
                'minval' => 18,
                'type'   => 'int'
            ]
        ]);

        $this->validation_2 = new Validation();
        $this->validation_2->setData($_POST);
        $this->validation_2->setFields([
            'name' => [
                'minlen' => 8,
                'type'   => 'alpha'
            ],
            'age' => [
                'minval' => 22,
                'maxval' => 23,
                'type'   => 'int'
            ]
        ]);
    }


    public function testInit()
    {
        $this->assertTrue($this->validation->isValid());
        $this->assertEmpty($this->validation->getInvalidValues());
        $this->assertFalse($this->validation_2->isValid());
        $this->assertNotEmpty($this->validation_2->getInvalidValues());
        $this->assertTrue((new Validation())->check([
            'is_bool' => [
                'type' => 'bool'
            ],
            'is_float' => [
                'minval' => 1.4,
                'maxval' => 1.5,
                'type' => 'float'
            ],
            'is_alphanum' => [
                'type' => 'alphanumeric'
            ]
        ],
        [
            'is_bool'     => 1,
            'is_float'    => 1.5,
            'is_alphanum' => 'wolff1234@getwolff.com'
        ]));
    }
}
