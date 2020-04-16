<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Template;

class TemplateTest extends TestCase
{

    const FILE = '../../test/core/template/original_view';
    const DATA = [
        'title' => 'Web development made just right',
        'html'  => '<p style="font-size:30px">Welcome!</p>'
    ];

    private $content;
    private $rendered_content;


    public function setUp(): void
    {
        $this->rendered_content = \file_get_contents('../test/core/template/expected_result.txt');
        $this->expected_result = Template::getRender(self::FILE, self::DATA, false);
    }


    public function testInit()
    {
        $this->assertEquals(
            $this->getWithoutSpaces($this->rendered_content),
            $this->getWithoutSpaces($this->expected_result)
        );
    }


    private function getWithoutSpaces(string $str)
    {
        return preg_replace('/\s+/', ' ', $str);
    }
}
