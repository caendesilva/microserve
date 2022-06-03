<?php

namespace App;

use Desilva\Microserve\HttpKernel as BaseKernel;
use Desilva\Microserve\Request;
use Desilva\Microserve\Response;

class HttpKernel extends BaseKernel
{
    public function handle(Request $request): Response
    {
        return (new Router($request))->handle();
    }
}
