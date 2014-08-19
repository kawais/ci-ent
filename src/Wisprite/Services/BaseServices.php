<?php
namespace Wisprite\Services;

class BaseServices
{
    public function say($hi = 'hello')
    {
        return $hi.$hi;
    }
}
