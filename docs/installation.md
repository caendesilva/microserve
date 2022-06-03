# Installation Quick Start

This document will guide you through creating a new minimal project.

You can of course use the concepts here to integrate Microserve into an existing project.

This package is targeted for developers, so this process will be mainly example-driven
through command-line actions and code snippets.

## Step 1: Create a new project
	$ mkdir example-project
	$ cd example-project
	$ composer require desilva/microserve

## Steb 1b: Configure composer settings

We'll use Composer for autoloading our classes. Add the following to your `composer.json` file:

```json
"autoload": {
	"psr-4": {
		"App\\": "src/"
	}
},
```

## Step 2: Create our HttpKernel

Microserve does not on its own know what to do with the incoming requests.

So we'll need an HttpKernel to handle routing and bootstrapping any logic.

The kernel is responsible for handling the incoming request and returning a response
and must implement the `HttpKernelInterface` contract.

Add the following to `src/HttpKernel.php`:

```php
<?php

namespace App;

class HttpKernel extends \Desilva\Microserve\HttpKernel
{
	
}
```

As you can see, we are not implementing the interface directly, instead,
we are extending the base HttpKernel class which gives us access to the following:

```php
<?php

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
```

## Step 3: Create the server.php file

Microserve expects that all requests will be routed to the `HttpKernel` class.
We can do this by creating a `server.php` file in our project root.

```php
<?php

// Load the composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Register the HttpKernel class
$kernel = \App\HttpKernel::class;

// Boot the application using our custom kernel
$app = \Desilva\Microserve\Microserve::boot($kernel);

// Run the application, handling and serving the request
$app->handle();

// Terminate the application
exit(0);
```

### Step 3b: Running the server

We are now ready to run the server. Locally, we can do this by running the following command:

```bash
php -S localhost:80 server.php
```

Now if we visit `http://localhost:80` we should see the following response:

```curl
curl http://localhost:80 -i

HTTP/1.1 200 OK

Hello World!
```

#### Step 3c: Running in production

If you are doing this in a production environment, you can use mod_rewrite to
redirect all requests to the `server.php` file. Microserve will inject a
`Request` instance to the `HttpKernel` which you can use for routing.

>warn Remember to keep your source files outside of your public HTML directory!


## Step 4: Next steps

What you do next is up to you! I highly suggest you look through the Microserve
source code to learn how it works, and how to use the features it provides.

You can also take a look at the example project to see a more functional example.