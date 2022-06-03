<?php

namespace Desilva\Microserve;

use Desilva\Microserve\Contracts\RequestInterface;
use JetBrains\PhpStorm\ArrayShape;

class Request implements RequestInterface
{
    public string $method;
    public string $path;
    public array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }

    public static function capture(): Request
    {
        return new self($_REQUEST);
    }

    public function __get(string $key): ?string
    {
        return $this->data[$key] ?? null;
    }

    #[ArrayShape(['method' => "string", 'path' => "string", 'data' => "array"])]
    public function __serialize(): array
    {
        return [
            'method' => $this->method,
            'path' => $this->path,
            'data' => $this->data,
        ];
    }
}