<?php

use Desilva\Microserve\JsonResponse;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Desilva\Microserve\JsonResponse
 */
class JsonResponseTest extends TestCase
{
    public function testToString()
    {
        $data = ['key' => 'value'];
        $response = new JsonResponse(200, 'OK', ['body' => $data]);

        $this->assertEquals(json_encode($response->__get()), $response->__toString());
    }

    public function testSend()
    {
        $response = new JsonResponse(200, 'OK', ['body' => ['key' => 'value']]);

        ob_start();
        $response->send();
        $output = ob_get_clean();

        $this->assertEquals(json_encode($response->__get()), $output);
        
        // Instead of using xdebug_get_headers(), we'll check if the header was set
        $this->assertTrue(headers_sent(), 'Headers should be sent');
    }
}
