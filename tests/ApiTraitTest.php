<?php

include "vendor/autoload.php";

class ApiTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated()
    {
        $c = new DummyController();
    }
}

class DummyController
{
    use \JimmyHowe\Utilities\ApiTrait\ApiTrait;
}