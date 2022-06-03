<?php

namespace Desilva\Microserve;

use Desilva\Microserve\Contracts\HttpKernelInterface;

class Microserve
{
    public const VERSION = 'dev-master';

    public static function boot()
    {
        return (new Application())->handle();
    }
}
