<?php

namespace Desilva\Microserve;

class JsonResponse extends Response
{
    public function __toString(): string
    {
        return json_encode($this->responseData);
    }

    public function send(): static
    {
        $this->withHeaders([
            'Content-Type' => 'application/json',
        ]);

        return parent::send();
    }
}
