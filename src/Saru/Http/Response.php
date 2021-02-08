<?php


namespace Saru\Http;


use Exception;

class Response
{
    private $contentType;
    private $body;
    private $httpCode;
    private $overrideParse;

    /**
     * Response constructor.
     * @param $contentType
     * @param $httpCode int Http code to be sent as response.
     * @param string $body
     * @param bool $overrideParse
     */
    public function __construct($contentType, $httpCode, $body = "", $overrideParse = false)
    {
        $this->contentType = $contentType;
        $this->body = $body;
        $this->httpCode = $httpCode;
        $this->overrideParse = $overrideParse;
    }

    public function sendResponse()
    {
        $this->setHeaders();
        $parsedBody = $this->parseBody();
        echo $parsedBody;
    }

    private function setHeaders()
    {
        $this->setHttpCode();
        $this->setContentType();
    }

    private function setHttpCode()
    {
        http_response_code($this->httpCode);
    }

    private function setContentType()
    {
        header("Content-type: {$this->contentType};charset=UTF-8");
    }

    private function parseBody()
    {
        if($this->overrideParse)
        {
            return $this->body;
        }
        if($this->contentType == "application/json")
        {
            return json_encode($this->body);
        }
        return $this->body;
    }

    public static function success($body): Response
    {
        return new Response("text/html", 200, $body);
    }

    public static function not_found($message = "Page not found", $contentType = "text/html"): Response
    {
        if($contentType == "json")
        {
            return new Response("application/json", 404, $message);
        }
        try {
            return Response::view("404", code: 404);
        } catch (Exception)
        {
            return new Response("text/html", 404, $message);
        }
    }

    public static function internal_server_error($message = "Internal server error", $contentType = "text/html"): Response
    {
        if($contentType == "json")
        {
            return new Response("application/json", 500, $message);
        }
        try {
            return Response::view("500", code: 500);
        } catch (Exception)
        {
            return new Response("text/html", 500, $message);
        }
    }

    public static function json($body, $httpCode = 200, $overrideParse = false): Response
    {
        return new Response("application/json", $httpCode, $body, $overrideParse);
    }

    public static function view($view, $code = 200): Response
    {
        try {
            require_once "../Views/{$view}.php";
            return new Response("text/html", $code);
        } catch(Exception) {
            return Response::internal_server_error();
        }
    }
}
