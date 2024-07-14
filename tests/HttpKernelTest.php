<?php

use Desilva\Microserve\HttpKernel;
use Desilva\Microserve\Request;
use Desilva\Microserve\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Desilva\Microserve\HttpKernel
 */
class HttpKernelTest extends TestCase
{
    public function testHandle()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $kernel = $this->getMockForAbstractClass(HttpKernel::class);
        $request = new Request();

        $response = $kernel->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals('Hello World!', $response->body);
    }

    public function testHandleSendsResponse()
    {
        $kernel = $this->getMockForAbstractClass(HttpKernel::class);
        $request = new Request();

        ob_start();
        $kernel->handle($request);
        $output = ob_get_clean();

        $this->assertEquals('Hello World!', $output);
    }
}
