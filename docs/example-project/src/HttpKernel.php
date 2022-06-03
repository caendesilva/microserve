<?php

namespace App;

use Desilva\Microserve\Request;
use Desilva\Microserve\Response;
use Desilva\Microserve\HttpKernel as BaseKernel;

class HttpKernel extends BaseKernel
{
	public function handle(Request $request): Response
	{
		return (new Router($request))->handle();
	}	
}