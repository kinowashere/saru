<?php

namespace Saru\Routing;
use Saru\Http\Response;

/**
* Perform all operations related to routing
*/
class Router
{
    private $urls;

    /**
     * Router constructor.
     * @param $urls
     */
    public function __construct($urls)
    {
        $this->urls = $urls;
    }

    /**
    * Get the controller namespace with the method that must be called from it.
    * @param string $reqPath Path from the request
     *
     * @return Response
    */
    public function getResponse($reqPath, $reqMethod)
    {
        try {
            $stringController = $this->getControllerMethod($reqPath, $reqMethod);
            return $this->callControllerMethod($stringController);
        } catch(RouterException $e) {
            return match ($e->getHttpCode()) {
                404 => Response::not_found($e->getMessage()),
                500 => Response::internal_server_error($e->getMessage()),
                default => Response::internal_server_error(),
            };
        }
    }

    /**
     * Find the Controller and the Class Method to be called given the request Path and HTTP Method
     * @param $reqPath
     * @param $reqMethod
     * @return mixed
     * @throws RouterException
     */
    public function getControllerMethod($reqPath, $reqMethod): mixed
    {
        foreach ($this->urls as $route)
        {
            if ($route->getPath() == $reqPath && $route->getMethod() == $reqMethod)
            {
                return $route->getController();
            }
        }

        throw new RouterException(message: "The page doesn't exist.", httpCode: 404);
    }

    /**
     * Calls on the Controller and returns the Controller's response
     * @param $stringController string Controller string in format "ControllerClass@ActionMethod"
     * @return mixed
     * @throws RouterException
     */
    public function callControllerMethod(string $stringController): mixed
    {
        $arrayControllerData = explode("@", $stringController);
        $controllerTemplate = "App\\Controller\\{$arrayControllerData[0]}";
        $controller = new $controllerTemplate;
        if (!method_exists($controller, $arrayControllerData[1]))
        {
            throw new RouterException(message: "Method doesn't exist", httpCode: 500);
        }
        return $controller->{$arrayControllerData[1]}();
    }
    
}