<?php
namespace Wisprite\Modules;

class UserModule
{
    public function sayHi($hi, $args = array())
    {
        echo $hi.' '.$args['name'];
    }
}
