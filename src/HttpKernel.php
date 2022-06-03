<?php

namespace Desilva\Microserve;

use Desilva\Microserve\Contracts\HttpKernelInterface;

abstract class HttpKernel implements HttpKernelInterface
{
    public function handle(Request $request): Response
    {
        return Response::make(200, 'OK', [
            'body' => 'Hello World!',
        ]);
    }
}