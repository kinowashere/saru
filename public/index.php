<?php

use Saru\Http\Request;
use Saru\Http\Response;
use Saru\Routing\Router;

require_once '../autoload.php';
require_once '../App/bootstrap.php';

try {
    $router = new Router($urls);
    $response = $router->getResponse(Request::uri(), Request::method());
    if(!$response || !method_exists($response, "sendResponse"))
    {
        throw new Exception();
    }
    $response->sendResponse();
} catch (Exception) {
    $response = Response::internal_server_error();
    $response->sendResponse();
}
