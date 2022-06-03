<?php

namespace Desilva\Microserve\Contracts;

interface ResponseInterface
{
    public function __construct(int $statusCode = 200, string $statusMessage = 'OK', array $data = []);

    public function __toString(): string;

    public function send(): void;
}
