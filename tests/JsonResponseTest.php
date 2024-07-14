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

    public function testWithHeaders()
    {
        $response = new JsonResponse();
        $response->withHeaders(['X-Test' => 'Value']);

        $reflector = new ReflectionClass($response);
        $headersProperty = $reflector->getProperty('headers');
        $headersProperty->setAccessible(true);

        $this->assertEquals(['X-Test' => 'Value'], $headersProperty->getValue($response));
    }

    public function testSend()
    {
        if (! function_exists('xdebug_get_headers')) {
            $this->markTestSkipped('Xdebug is not installed.');
        }

        $response = new JsonResponse(200, 'OK', ['body' => ['key' => 'value']]);

        ob_start();
        $result = $response->send();
        $output = ob_get_clean();

        $this->assertEquals(json_encode($response->__get()), $output);
        $this->assertSame($response, $result);

        $headers = xdebug_get_headers();
        $this->assertContains('Content-Type: application/json', $headers);
    }
}
