<?php

use Jalejandro\DecampoacampoChallenge\Http\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require __DIR__ . '/../vendor/autoload.php';
$controllers = require __DIR__ . '/../bootstrap.php';
$routes = require __DIR__ . '/../config/routes.php';

$request = Request::createFromGlobals();
$context = new RequestContext()->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    $parameters = $matcher->match($request->getPathInfo());
    $controller = $controllers[$parameters['_controller']];
    $routeParams = array_filter($parameters, fn($key) => !str_starts_with($key, '_'), ARRAY_FILTER_USE_KEY);
    $response = $controller(...$routeParams);
} catch (Throwable $e) {
    $response = new ExceptionHandler()->handle($e);
}

$response->send();