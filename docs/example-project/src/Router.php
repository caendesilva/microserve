<?php

namespace App;

use Desilva\Microserve\JsonResponse;
use Desilva\Microserve\Request;
use Desilva\Microserve\Response;
use PHPUnit\Util\Json;

/**
 * Simple Router Proof of Concept
 */
class Router
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(): Response
    {
        if ($this->is('') || $this->is('/index')) {
            return Response::make(200, 'OK', [
                'body' => file_get_contents(__DIR__.'/views/index.html'),
            ]);
        }

        if ($this->is('json')) {
            return JsonResponse::make(200, 'OK', [
                'message' => 'Hello World!',
            ]);
        }

        if  ($this->is('forms')) {
            return Response::make(200, 'OK', [
                'body' => $this->handleFormPage(),
            ]);
        }

        return Response::make(404, 'Not Found');
    }

    protected function is(string $path): bool
    {
        return $this->request->path === '/' . $path;
    }

    protected function handleFormPage(): string
    {
        $html = file_get_contents(__DIR__.'/views/forms.html');

        return ($this->request->get('name', false))
            ? str_replace('{{% Request Name %}}', 'Your name is: ' . htmlspecialchars($this->request->get('name')), $html)
            : str_replace('{{% Request Name %}}', '', $html);
    }
}