# Microserve - Experimental Pikoserve 2.0 Framework

An extension of Pikoserve, providing an API for creating PHP application servers.

Minimal. Agnostic. Zero dependencies.

## About

This package provides a framework for creating application servers and is intended to be
used as a starting point for other packages. Think if this as a layer between the low-level
PHP server implementations and your higher level application logic, allowing you to interact
with the requests and responses in an abstracted object-oriented way that you may be used to.

The name comes from it being based on [Pikoserve]https://github.com/caendesilva/pikoserve.

## Usage

### High level overview

You'll need to take care of bootstrapping yourself as each use case is different.

In general, you'll want to route all requests to a single entry point, which should
be an extension of the HttpKernelInterface. This is where you would bind a router
or similar to handle the requests.

#### General implementation

The recommended way to implement a server  is to route all HTTP requests to the `server.php`script.
This script should register the Composer autoloader, run the `bootstrap.php` script, then finally
create a new `Application` instance to capture and handle the incoming HTTP request.