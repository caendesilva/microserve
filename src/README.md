# Microserve - Experimental Pikoserve Framework

An extension of Pikoserve, providing an API for defining PHP application servers.

## Usage

You'll need to take care of bootstrapping yourself as each use case is different.

In general, you'll want to route all requests to a single entry point, which should
be an extension of the HttpKernelInterface. You must then register your HttpKernel
implementation by passing the class name to the Application constructor.

