<?php
namespace Training\Api\Model;

use Training\Api\Api\Data;

class Hello implements \Training\Api\Api\Data\HelloInterface
{
    public function sayHello() {
        return "HELLO WORLD!";
    }
}