<?php
namespace Wisprite\Tests;

use Wisprite\Services;

class BaseServicesTest extends \PHPUnit_Framework_TestCase
{
    public function testsay()
    {
        $s = new Services\BaseServices();
        $say = 'hi';
        $this->assertEquals($say, $s->say($say));
        $this->assertEquals('hello', $s->say());
    }
}
