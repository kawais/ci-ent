<?php
namespace Wisprite\Services;

use Wisprite\Modules;

class AuthServices extends BaseServices
{
    public function m()
    {
        $m = new Modules\UserModule();
        return $m;
    }
}
