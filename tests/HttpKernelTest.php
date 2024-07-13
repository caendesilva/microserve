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
        $kernel = $this->getMockForAbstractClass(HttpKernel::class);
        $request = new Request();

        $response = $kernel->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals('Hello World!', $response->body);
    }
}
