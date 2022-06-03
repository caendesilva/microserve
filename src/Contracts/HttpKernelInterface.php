<?php

namespace Desilva\Microserve\Contracts;

use Desilva\Microserve\Request;
use Desilva\Microserve\Response;

interface HttpKernelInterface
{
    public function handle(Request $request): Response;
}
