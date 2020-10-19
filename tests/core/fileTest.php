<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Http\File;

class FileTest extends TestCase
{

    private $file;


    public function setUp(): void
    {
        $options = [
            'max_size'   => 1000,
            'dir'        => '/',
            'override'   => true,
        ];

        $this->file = new File([
            'name'     => 'image.jpg',
            'tmp_name' => '',
            'size'     => 1024,
        ], $options);
    }


    public function testInit()
    {
        $this->assertEquals('image.jpg', $this->file->get('name'));
        $this->assertFalse($this->file->upload());
    }
}
