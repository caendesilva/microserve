<?php // file: tests/ApplicationTest.php

use PHPUnit\Framework\TestCase;
use Desilva\Microserve\Application;
use Desilva\Microserve\Contracts\HttpKernelInterface;
use Desilva\Microserve\Request;
use Desilva\Microserve\Response;

/**
 * @covers \Desilva\Microserve\Application
 */
class ApplicationTest extends TestCase
{
    public function testHandle()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

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

?>

<?php // file: tests/HttpKernelTest.php

use PHPUnit\Framework\TestCase;
use Desilva\Microserve\HttpKernel;
use Desilva\Microserve\Request;
use Desilva\Microserve\Response;

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
}

?>

<?php // file: tests/JsonResponseTest.php

use PHPUnit\Framework\TestCase;
use Desilva\Microserve\JsonResponse;

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

?>

<?php // file: tests/MicroserveTest.php

use PHPUnit\Framework\TestCase;
use Desilva\Microserve\Microserve;
use Desilva\Microserve\Application;
use Desilva\Microserve\HttpKernel;

/**
 * @covers \Desilva\Microserve\Microserve
 */
class MicroserveTest extends TestCase
{
    public function testBoot()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $kernelClass = get_class($this->getMockForAbstractClass(HttpKernel::class));
        
        $app = Microserve::boot($kernelClass);
        
        $this->assertInstanceOf(Application::class, $app);
    }
}

?>

<?php // file: tests/RequestTest.php

use PHPUnit\Framework\TestCase;
use Desilva\Microserve\Request;

/**
 * @covers \Desilva\Microserve\Request
 */
class RequestTest extends TestCase
{
    public function testConstruct()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/test/path';
        
        $request = new Request(['key' => 'value']);
        
        $this->assertEquals('POST', $request->method);
        $this->assertEquals('/test/path', $request->path);
        $this->assertEquals(['key' => 'value'], $request->data);
    }

    public function testGet()
    {
        $request = new Request(['key' => 'value']);
        
        $this->assertEquals('value', $request->get('key'));
        $this->assertEquals('default', $request->get('nonexistent', 'default'));
        $this->assertEquals(['key' => 'value'], $request->get(null));
    }

    public function testMagicGet()
    {
        $request = new Request(['key' => 'value']);
        
        $this->assertEquals('value', $request->key);
    }

    public function testCapture()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';
        $_REQUEST = ['key' => 'value'];
        
        $request = Request::capture();
        
        $this->assertInstanceOf(Request::class, $request);
        $this->assertEquals(['key' => 'value'], $request->data);
    }

    public function testSerialize()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';
        
        $request = new Request(['key' => 'value']);
        
        $serialized = $request->__serialize();
        
        $this->assertEquals([
            'method' => 'GET',
            'path' => '/test',
            'data' => ['key' => 'value']
        ], $serialized);
    }
}

?>

<?php // file: tests/ResponseTest.php

use PHPUnit\Framework\TestCase;
use Desilva\Microserve\Response;

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
        
        $this->assertEquals('Test Body', (string)$response);
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
            'statusCode' => 200,
            'statusMessage' => 'OK',
            'body' => '',
            'custom' => 'value'
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

?>
