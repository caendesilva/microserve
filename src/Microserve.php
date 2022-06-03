<?php

namespace Desilva\Microserve;

class Microserve
{
    public const VERSION = 'dev-master';

    /**
     * Microserve does not know how to handle the request,
     * so you'll need an HttpKernel to handle routing.
     *
     * @param string $kernel
     *
     * @return Application
     */
    public static function boot(string $kernel): Application
    {
        return new Application(new $kernel());
    }
}
