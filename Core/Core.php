<?php

namespace core;

require_once 'Loader.php';


class Core
{

    private $loader;

    public function __construct()
    {
        $this->loader = new Loader();
    }

}