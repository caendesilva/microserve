<?php

namespace Desilva\Microserve;

use Desilva\Microserve\Contracts\HttpKernelInterface;

class Application
{
    protected Request $request;
    protected HttpKernelInterface $kernel;

    public function __construct(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->request = Request::capture();
    }
    
    public function handle(): int
    {
        $response = $this->kernel->handle($this->request);
        $response->send();

        return $response->getData('statusCode');
    }
}