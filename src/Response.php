<?php

namespace Desilva\Microserve;

use Desilva\Microserve\Contracts\ResponseInterface;

class Response implements ResponseInterface
{
    protected array $responseData;

    public function __construct(int $statusCode = 200, string $statusMessage = 'OK', array $data = [])
    {
        header("HTTP/1.1 $statusCode $statusMessage");

        $this->responseData = array_merge([
            'statusCode'    => $statusCode,
            'statusMessage' => $statusMessage,
            'body'          => '',
        ], $data);
    }

    public function __toString(): string
    {
        return $this->responseData['body'];
    }

    public function withHeaders(array $headers): self
    {
        foreach ($headers as $header => $value) {
            header("$header: $value");
        }

        return $this;
    }

    public function send(): void
    {
        echo $this;
    }

    public function __get(?string $key = null): mixed
    {
        if ($key) {
            return $this->responseData[$key] ?? null;
        }

        return $this->responseData;
    }

    /**
     * Static facade to create a new Response object.
     * You will still need to call send() to actually send the response.
     */
    public static function make(int $statusCode = 200, string $statusMessage = 'OK', array $data = []): static
    {
        return new static($statusCode, $statusMessage, $data);
    }
}
