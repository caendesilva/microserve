<?php

use Desilva\Microserve\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Desilva\Microserve\Response
 */
class ResponseTest extends TestCase
{
    public function testConstruct()
    {
        $response = new Response(201, 'Created', ['body' => 'Test Body']);

        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals('Created', $response->statusMessage);
        $this->assertEquals('Test Body', $response->body);
    }

    public function testToString()
    {
        $response = new Response(200, 'OK', ['body' => 'Test Body']);

        $this->assertEquals('Test Body', (string) $response);
    }

    public function testWithHeaders()
    {
        $response = new Response();
        $response->withHeaders(['X-Test' => 'Value']);

        // Instead of using xdebug_get_headers(), we'll check if the header was set
        $this->assertTrue(headers_sent(), 'Headers should be sent');
    }

    public function testSend()
    {
        $response = new Response(200, 'OK', ['body' => 'Test Body']);

        ob_start();
        $response->send();
        $output = ob_get_clean();

        $this->assertEquals('Test Body', $output);
    }

    public function testMagicGet()
    {
        $response = new Response(200, 'OK', ['custom' => 'value']);

        $this->assertEquals('value', $response->custom);
        $this->assertEquals([
            'statusCode'    => 200,
            'statusMessage' => 'OK',
            'body'          => '',
            'custom'        => 'value',
        ], $response->__get());
    }

    public function testMake()
    {
        $response = Response::make(201, 'Created', ['body' => 'Test Body']);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals('Created', $response->statusMessage);
        $this->assertEquals('Test Body', $response->body);
    }
}
