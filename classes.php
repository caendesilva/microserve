<?php // file: Application.php

namespace Desilva\Microserve;

use Desilva\Microserve\Contracts\HttpKernelInterface;

class Application
{
    protected Request $request;
    protected HttpKernelInterface $kernel;

    public function __construct(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->request = Request::capture();
    }

    public function handle(): Response
    {
        return $this->kernel->handle($this->request);
    }
}
?>

<?php // file: Contracts\HttpKernelInterface.php

namespace Desilva\Microserve\Contracts;

use Desilva\Microserve\Request;
use Desilva\Microserve\Response;

interface HttpKernelInterface
{
    public function handle(Request $request): Response;
}
?>

<?php // file: Contracts\RequestInterface.php

namespace Desilva\Microserve\Contracts;

interface RequestInterface
{
    public function __construct(array $data = []);

    public function __serialize(): array;

    public function __get(string $key);

    public function get(?string $key, $default = null);

    public static function capture(): static;
}
?>

<?php // file: Contracts\ResponseInterface.php

namespace Desilva\Microserve\Contracts;

interface ResponseInterface
{
    public function __construct(int $statusCode = 200, string $statusMessage = 'OK', array $data = []);

    public function __toString(): string;

    public function send(): void;
}
?>

<?php // file: HttpKernel.php

namespace Desilva\Microserve;

use Desilva\Microserve\Contracts\HttpKernelInterface;

abstract class HttpKernel implements HttpKernelInterface
{
    public function handle(Request $request): Response
    {
        return Response::make(200, 'OK', [
            'body' => 'Hello World!',
        ]);
    }
}
?>

<?php // file: JsonResponse.php

namespace Desilva\Microserve;

class JsonResponse extends Response
{
    public function __toString(): string
    {
        return json_encode($this->responseData);
    }

    public function send(): void
    {
        $this->withHeaders([
            'Content-Type' => 'application/json',
        ]);

        parent::send();
    }
}
?>

<?php // file: Microserve.php

namespace Desilva\Microserve;

class Microserve
{
    /**
     * Microserve does not know how to handle the request,
     * so you'll need an HttpKernel to handle routing.
     *
     * @param string $kernel
     *
     * @return Application
     */
    public static function boot(string $kernel): Application
    {
        return new Application(new $kernel());
    }
}
?>

<?php // file: Request.php

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
?>

<?php // file: Response.php

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
?>
