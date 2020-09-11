<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Template;
use Wolff\Exception\FileNotFoundException;

class TemplateTest extends TestCase
{

    public function setUp(): void
    {
        Template::setStatus(true);
        Template::custom(function ($content) {
            return str_replace('bar', 'foo', $content);
        });
    }


    public function testInit()
    {
        $this->assertTrue(Template::isEnabled());
        $this->assertEquals(
            $this->getWithoutSpaces(\file_get_contents('../test/core/template/expected.txt')),
            $this->getWithoutSpaces(Template::getRender('../../test/core/template/original_view', [
                'title' => 'Web development made just right.',
                'html'  => '<p style="font-size:30px">Welcome!</p>'
            ], false))
        );

        $this->assertEquals(
            $this->getWithoutSpaces(\file_get_contents('../test/core/template/expected_extended.txt')),
            $this->getWithoutSpaces(Template::getRender('../../test/core/template/child_view', [], false))
        );

        $this->expectException(FileNotFoundException::class);

        Template::get('non_existing_file', [], false);
    }


    private function getWithoutSpaces(string $str)
    {
        return preg_replace('/\s+/', ' ', $str);
    }
}
