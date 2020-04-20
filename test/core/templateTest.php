<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Template;

class TemplateTest extends TestCase
{

    const FILE = '../../test/core/template/original_view';
    const EXTENDED_FILE = '../../test/core/template/child_view';
    const DATA = [
        'title' => 'Web development made just right',
        'html'  => '<p style="font-size:30px">Welcome!</p>'
    ];

    private $content;
    private $expected;

    private $extended_content;
    private $extended_expected;


    public function setUp(): void
    {
        $this->content = \file_get_contents('../test/core/template/expected.txt');
        $this->expected = Template::getRender(self::FILE, self::DATA, false);
        $this->extended_content = \file_get_contents('../test/core/template/expected_extended.txt');
        $this->extended_expected = Template::getRender(self::EXTENDED_FILE, [], false);
    }


    public function testInit()
    {
        $this->assertEquals(
            $this->getWithoutSpaces($this->content),
            $this->getWithoutSpaces($this->expected)
        );

        $this->assertEquals(
            $this->getWithoutSpaces($this->extended_content),
            $this->getWithoutSpaces($this->extended_expected)
        );
    }


    private function getWithoutSpaces(string $str)
    {
        return preg_replace('/\s+/', ' ', $str);
    }
}
