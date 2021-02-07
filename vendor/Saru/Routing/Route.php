<?php

namespace Saru\Routing;

class Route
{
    private $path;
    private $method;
    private $controller;

    function __construct($path, $method, $controller)
    {
        $this->path = $path;
        $this->method = $method;
        $this->controller = $controller;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getController()
    {
        return $this->controller;
    }
}
