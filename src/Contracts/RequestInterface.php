<?php

namespace Desilva\Microserve\Contracts;

use Desilva\Microserve\Request;

interface RequestInterface
{
    public function __construct(array $data = []);
    public function __get(string $key): ?string;
    public function __serialize(): array;

    public static function capture(): Request;
}