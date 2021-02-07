<?php

use Saru\Routing\Route;

return  [
    new Route("/", "GET", "HomeController@index")
];
