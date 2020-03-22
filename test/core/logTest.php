<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Wolff\Core\Log;

class logTest extends TestCase
{

    public function testInit()
    {
        $log_file = CONFIG['system_dir'] . 'logs/' . date('m-d-Y') . '.log';
        Log::info('testing creation of file');

        $this->assertFileExists($log_file);
    }

}
