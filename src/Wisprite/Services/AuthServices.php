<?php
namespace Wisprite\Services;

use Wisprite\Modules;

class AuthServices extends BaseServices {

    function test() {
        $m = new Modules\UserModule();
        var_dump($m);
    }
}