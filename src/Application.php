<?php

namespace Desilva\Microserve;

use Desilva\Microserve\Contracts\HttpKernelInterface;

class Application
{
    protected Request $request;
    protected HttpKernelInterface $routeHandler;

    public function __construct(HttpKernelInterface $routeHandler = null)
    {
        $this->routeHandler = $routeHandler ?? new HttpKernel();
        $this->request = Request::capture();
    }

    public function handle(): Response
    {
        return (new $this->routeHandler())->handle($this->request);
    }

    public function request(): Request
    {
        return $this->request;
    }
}