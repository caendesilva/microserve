<?php

namespace Desilva\Microserve;

class HtmlResponse extends Response
{
    public function send(): static
    {
        $this->withHeaders([
            'Content-Type' => 'text/html',
            'Content-Length' => strlen($this->responseData['body']),
        ]);

        return parent::send();
    }
}
