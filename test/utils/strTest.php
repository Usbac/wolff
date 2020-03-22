<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Utils\Str;

class strTest extends TestCase
{

    public function testInit()
    {
        $values = [
            'first' => 'john',
            'last'  => 'doe'
        ];

        $this->assertEquals('home/helloworld', Str::sanitizeURL('home/hello world'));
        $this->assertEquals('contact@getwolff.com', Str::sanitizeEmail('contact@get//wolff.com'));
        $this->assertEquals('255', Str::sanitizeInt('2.5//5'));
        $this->assertEquals('2.55', Str::sanitizeFloat('2.5//5'));
        $this->assertEquals('app/controllers/home.php', Str::sanitizePath('app/{controllers}/home.php'));
        $this->assertEquals('hola-como-estas-bien', Str::slug(' Hola cómo estás? Bien'));
        $this->assertEquals('Your firstname is john and your lastname is doe', Str::interpolate('Your firstname is {first} and your lastname is {last}', $values));
        $this->assertEquals('I\'m the Omega, the Alpha, everything in between', Str::swap('I\'m the Alpha, the Omega, everything in between', 'Alpha', 'Omega'));
        $this->assertEquals('Lore', Str::limit('Lorem ipsum dolor sit amet', '4'));
        $this->assertEquals('Lorem ipsum dolor sit amet', Str::unShift('psum dolor sit amet', 'Lorem i'));
        $this->assertEquals('home/public/logo.svg', Str::concatPath('home', 'public', 'logo.svg'));
        $this->assertEquals('Lorem ipsum dolor', Str::concat('Lorem ', 'ipsum ', 'dolor'));
        $this->assertEquals('true', Str::toString(true));
        $this->assertEquals('Lorem ipsum dolor  amet', Str::remove('Lorem ipsum dolor sit amet', 'sit'));
        $this->assertEquals(' sit amet', Str::after('Lorem ipsum dolor sit amet', 'dolor'));
        $this->assertEquals('Lorem ipsum ', Str::before('Lorem ipsum dolor sit amet', 'dolor'));
        $this->assertTrue(Str::isEmail('contact@getwolff.com'));
        $this->assertTrue(Str::isAlphanumeric('abcdefg1234567 890'));
        $this->assertTrue(Str::isAlpha('abc def g'));
        $this->assertTrue(Str::contains('Lorem ipsum dolor sit amet', 'sit'));
        $this->assertTrue(Str::startsWith('Lorem ipsum dolor sit amet', 'Lorem'));
        $this->assertTrue(Str::endsWith('Lorem ipsum dolor sit amet', 'amet'));
    }

}
