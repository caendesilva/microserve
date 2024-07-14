# Microserve - API for creating PHP application servers.

### Minimal. Agnostic. Zero dependencies.

## About

![](https://img.shields.io/packagist/dt/desilva/microserve)
![](https://img.shields.io/packagist/v/desilva/microserve)

This package provides a framework for creating application servers and is intended to be
used as a starting point for other packages. Think if this as a layer between the low-level
PHP server implementations and your higher level application logic, allowing you to interact
with the requests and responses in an abstracted object-oriented way that you may be used to.
The name comes from it being based on [Pikoserve](https://github.com/caendesilva/pikoserve).

## Installation
Install the package using Composer ([desilva/microserve](https://packagist.org/packages/desilva/microserve))

```bash
composer require desilva/microserve
```

## Usage

Please see the example project in the [`docs`](docs/installation.md) directory.

This project is also used for the server core of the [HydePHP Realtime Compiler (V2.0)](https://github.com/hydephp/realtime-compiler),
I recommend you take a look at the implementation there as well.

### High level overview

You'll need to take care of bootstrapping yourself as each use case is different.

In general, you'll want to route all requests to a single entry point, which should
be an extension of the HttpKernelInterface. This is where you would bind a router
or similar to handle the requests.

#### General implementation

The recommended way to implement a server is to route all HTTP requests to the `server.php` script.
This script should register the Composer autoloader, run the `bootstrap.php` script, then finally
create a new `Application` instance to capture and handle the incoming HTTP request.

Here's an example of a `server.php` script:
```php
require_once 'vendor/autoload.php';

$app = \Desilva\Microserve\Microserve::boot(\App\Http\MyHttpKernel::class);
$app->handle() // Process the request and create the response
    ->send(); // Send the response to the client
```

The `boot()` method will construct your Kernel, and then return an `Application` instance containing it.

We then call the `handle()` method which will inject a `Request` object, then call the Kernel handle method
which returns a `Response` object which is used to send the HTTP response to the client.

HttpKernel example:
```php
class HttpKernel implements HttpKernelInterface
{
    public function handle(Request $request): Response
    {
        return Response::make(200, 'OK', [
            'body' => 'Hello World!',
        ]);
    }
}
```

### Troubleshooting

In 99% of the cases, you forgot to call the `->send()` method on your `Response` instance. For the other 1%, open a ticket and let me know.
