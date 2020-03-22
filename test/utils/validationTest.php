<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Utils\Validation;

class validationTest extends TestCase
{

    private $validation, $validation_2;


    public function setUp(): void
    {
        $data_1 = [
            'name' => 'Thomas Andrews',
            'age'  => 39
        ];

        $fields_1 = [
            'name' => [
                'minlen' => 10,
                'type'   => 'alpha'
            ],
            'age' => [
                'minval' => 18,
                'type'   => 'int'
            ]
        ];

        $_POST = [
            'name' => 'Alejandro',
            'age'  => 24
        ];

        $fields_2 = [
            'name' => [
                'minlen' => 8,
                'type'   => 'alpha'
            ],
            'age' => [
                'minval' => 22,
                'maxval' => 23,
                'type'   => 'int'
            ]
        ];

        $this->validation = new Validation();
        $this->validation->setData($data_1);
        $this->validation->setFields($fields_1);

        $this->validation_2 = new Validation();
        $this->validation_2->setData($_POST);
        $this->validation_2->setFields($fields_2);
    }


    public function testInit()
    {
        $this->assertTrue($this->validation->isValid());
        $this->assertEmpty($this->validation->getInvalidValues());
        $this->assertFalse($this->validation_2->isValid());
        $this->assertNotEmpty($this->validation_2->getInvalidValues());
    }

}
