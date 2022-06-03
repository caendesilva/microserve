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