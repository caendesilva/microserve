<?php

namespace Desilva\Microserve;

use Desilva\Microserve\Contracts\HttpKernelInterface;

class Application
{
    protected Request $request;
    protected HttpKernelInterface $routeHandler;

    public function __construct(HttpKernelInterface $routeHandler)
    {
        $this->routeHandler = $routeHandler;
        $this->request = Request::capture();
    }
    
    public function handle(): int
    {
        $response = $this->routeHandler->handle($this->request);
        $response->send();

        return $response->getData('statusCode');
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}