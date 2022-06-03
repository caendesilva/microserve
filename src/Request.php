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

    public static function capture(): static
    {
        return new static($_REQUEST);
    }

    public function __get(string $key)
    {
        return $this->get($key);
    }

    public function get(?string $key, $default = null)
    {
        if ($key === null) {
            return $this->data ?? [];
        }

        return $this->data[$key] ?? $default;
    }

    #[ArrayShape(['method' => 'string', 'path' => 'string', 'data' => 'array'])]
    public function __serialize(): array
    {
        return [
            'method' => $this->method,
            'path'   => $this->path,
            'data'   => $this->data,
        ];
    }
}
