# Embryo CORS
Simple PSR-15 Middleware to implement Cross-Origin Resource Sharing (CORS).

## Requirements
* PHP >= 7.1
* A [PSR-7](https://www.php-fig.org/psr/psr-7/) http message implementation and [PSR-17](https://www.php-fig.org/psr/psr-17/) http factory implementation (ex. [Embryo-Http](https://github.com/davidecesarano/Embryo-Http))
* A [PSR-15](https://www.php-fig.org/psr/psr-15/) http server request handlers implementation (ex. [Embryo-Middleware](https://github.com/davidecesarano/Embryo-Middleware))

## Install
Using Composer:
```
$ composer require davidecesarano/embryo-cors
```
## Usage
```php
use Embryo\CORS\CorsMiddleware;
use Embryo\Http\Factory\{ResponseFactory, ServerRequestFactory};
use Embryo\Http\Server\RequestHandler;

// Set options
$allowed_origins = ['*'];
$allowed_methods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
$allowed_headers = ['Content-Type', 'Accept', 'Origin', 'Authorization'];
$exposed_headers = [];
$max_age = 0;
$supports_credentials = false;

// Add middleware to dispatcher
$requestHandler = new RequestHandler([
    (new CorsMiddleware)
        ->setAllowedOrigins($allowed_origins)
        ->setAllowedMethods($allowed_methods)
        ->setAllowedHeaders($allowed_headers)
        ->setExposedHeaders($exposed_headers)
        ->setMaxAge($max_age)
        ->setSupportsCredentials($supports_credentials)
]);

// Set PSR Request and Response
$request = (new ServerRequestFactory)->createServerRequestFromServer();
$response = (new ResponseFactory)->createResponse(200);

$response = $requestHandler->dispatch($request, $response);
```
