<?php

use Desilva\Microserve\Request;
use PHPUnit\Framework\TestCase;

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
            'data' => ['key' => 'value'],
        ], $serialized);
    }
}
