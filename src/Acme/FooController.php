<?php

namespace Acme;

use Florent\FordPress;

class FooController
{
    public static function bar()
    {
        FordPress::render('welcome');
    }
}