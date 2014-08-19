<?php
namespace Wisprite\Tests;

use Wisprite\Services;

class AuthServicesTest extends \PHPUnit_Framework_TestCase
{
    public function testm()
    {
        $s = new Services\AuthServices();

        $this->assertInstanceOf("Wisprite\Modules\BaseModule", $s->m());
    }
}
