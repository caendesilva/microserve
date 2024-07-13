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
        
        $this->assertTrue(headers_sent(), 'Headers should be sent');
    }
}
