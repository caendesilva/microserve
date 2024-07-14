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
$app->handle(); // Process the request to create and send the response
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

Note: If you use Response::make() it will automatically send the response to the client. 
If you don't want this, you can instead update your kernel handle method to use `(new Response(...))`,
and then call the `send()` method manually on the response object returned from the handle method.

## Release Notes for v2.0

### Breaking/Major Changes
- Breaking: The `ResponseInterface::send()` method now returns `static` instead of `void`. This change affects the interface and all implementing classes.
- Major: `Response::make()` now automatically sends the response. This change affects how responses are created and sent using the static `make()` method.

### New Features
- Headers are now buffered in the Response class instead of being sent immediately.
- New protected `sendHeaders()` method added to the Response class for sending all buffered headers.

### Improvements
- The `Response::send()` and `JsonResponse::send()` methods now return `$this`, allowing for method chaining and providing more flexibility when working with responses.
- The `Response::make()` method now sends the response immediately after creating it. This fixes a common "gotcha" where users forget to call `send()` after creating a response.
- Type hints now use `static` returns instead of `self` to more accurately reflect the return type of the methods.
- More flexibility in manipulating headers throughout the response lifecycle.
- Better alignment with common practices in modern PHP frameworks.

### Upgrade Guide

If you're upgrading from v1.x to v2.0, here are the key changes you need to be aware of:

#### Response::send() Method Return Type

1. The `send()` method in the `ResponseInterface` now has a return type of `static`. This is a breaking change as is requires all implementing classes to update their method signature.
2. If you have any custom classes implementing `ResponseInterface`, you must update their `send()` method to return `static`:

   ```php
   public function send(): static
   {
       // Your implementation

       return $this;
   }
   ```

Please review your codebase for any implementations of `ResponseInterface` and update them accordingly. This change is made to allow for method chaining and provide more flexibility when working with responses, and to allow for working with sent responses in a more fluent way.

#### Response::make() Method Behavior

1. The `make()` method in the `Response` class now automatically sends the response. This is a major change as it affects how responses are created and sent.
2. If you were previously using `Response::make()->send()`, you should now use just `Response::make()` so you don't try to send the response twice.
3. If you need to create a response without sending it immediately, use the constructor `new Response()` instead of `Response::make()`.

Example of updated usage:

```php
// Old way
$response = Response::make(200, 'OK', ['body' => 'Hello World!']);
$response->send();

// New way
Response::make(200, 'OK', ['body' => 'Hello World!']);
```

Please review your codebase for any uses of `Response::make()` and update them accordingly. This change is made to simplify the API and provide a more intuitive way of creating and sending responses.

#### Header Sending Changes

1. The `withHeaders()` method now adds headers to a buffer instead of sending them immediately. If you were relying on immediate header sending, you may need to adjust your code.
2. Headers are now sent when the `send()` method is called on the Response object. Make sure you're calling `send()` at the appropriate time in your application lifecycle.
3. If you've extended the Response or JsonResponse classes, you may need to update your implementations to work with the new buffered header approach.
4. Update any tests that were checking for immediate header sending. You may need to use reflection or mock the header functions to test the new buffering behavior.

#### Conclusion

If you encounter any issues during the upgrade process, please open an issue on the GitHub repository.
