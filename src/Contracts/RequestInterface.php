<?php

namespace Desilva\Microserve\Contracts;

interface RequestInterface
{
    public function __construct(array $data = []);

    public function __serialize(): array;

    public function __get(string $key);

    public function get(?string $key, $default = null);

    public static function capture(): static;
}
