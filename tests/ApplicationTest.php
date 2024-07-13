<?php

use Desilva\Microserve\Application;
use Desilva\Microserve\Contracts\HttpKernelInterface;
use Desilva\Microserve\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Desilva\Microserve\Application
 */
class ApplicationTest extends TestCase
{
    public function testHandle()
    {
        $mockKernel = $this->createMock(HttpKernelInterface::class);
        $mockKernel->expects($this->once())
            ->method('handle')
            ->willReturn(new Response(200, 'OK', ['body' => 'Test Response']));

        $app = new Application($mockKernel);
        $response = $app->handle();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->statusCode);
        $this->assertEquals('Test Response', $response->body);
    }
}
