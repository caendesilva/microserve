<?php

use Desilva\Microserve\HtmlResponse;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Desilva\Microserve\HtmlResponse
 */
class HtmlResponseTest extends TestCase
{
    public function testSend()
    {
        if (! function_exists('xdebug_get_headers')) {
            $this->markTestSkipped('Xdebug is not installed.');
        }

        $htmlContent = '<html><body><h1>Test</h1></body></html>';
        $response = new HtmlResponse(200, 'OK', ['body' => $htmlContent]);

        ob_start();
        $result = $response->send();
        $output = ob_get_clean();

        $this->assertEquals($htmlContent, $output);
        $this->assertSame($response, $result);

        $headers = xdebug_get_headers();
        $this->assertContains('Content-type: text/html;charset=UTF-8', $headers);
        $this->assertContains('Content-Length: '.strlen($htmlContent), $headers);
    }

    public function testInheritance()
    {
        $response = new HtmlResponse();
        $this->assertInstanceOf(\Desilva\Microserve\Response::class, $response);
    }

    public function testCustomStatusAndMessage()
    {
        $response = new HtmlResponse(201, 'Created', ['body' => '<html><body>Resource created</body></html>']);

        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals('Created', $response->statusMessage);
    }
}
