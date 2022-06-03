<?php

namespace Desilva\Microserve;

class JsonResponse extends Response
{
    public function __toString(): string
    {
        return json_encode($this->responseData);
    }

    public function send(): void
    {
        header('Content-Type: application/json');

        echo $this;
    }
}