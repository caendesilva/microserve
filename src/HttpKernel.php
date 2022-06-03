<?php

namespace Desilva\Microserve;

use Desilva\Microserve\Contracts\HttpKernelInterface;

abstract class HttpKernel implements HttpKernelInterface
{
    public function handle(Request $request): Response
    {
        return new Response(200, 'OK', [
            'body' => 'Hello World!',
        ]);
    }
}