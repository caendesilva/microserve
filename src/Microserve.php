<?php

namespace Desilva\Microserve;

class Microserve
{
    public const VERSION = 'dev-master';

    /**
     * Microserve does not know how to handle the request,
     * so you need to supply a custom handler, usually the HttpKernel.
     *
     * @param string $routeHandler
     * @return Application
     */
    public static function boot(string $routeHandler): Application
    {
        return (new Application(new $routeHandler()));
    }
}
