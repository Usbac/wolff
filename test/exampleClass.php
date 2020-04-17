<?php

namespace Test;

class ExampleClass
{

    private $param1;
    private $param2;


    public function __construct($param1, $param2)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
    }


    public function setParam1($param)
    {
        $this->param1 = $param;
    }


    public function setParam2($param)
    {
        $this->param2 = $param;
    }


    public function getParam1()
    {
        return $this->param1;
    }


    public function getParam2()
    {
        return $this->param2;
    }
}
