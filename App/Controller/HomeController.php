<?php


namespace App\Controller;


use App\Model\Monkey;
use Saru\Http\Response;

class HomeController
{
    function index()
    {
        $properties = [
            "id" => 1,
            "name" => "Saruman",
            "species" => "Nihonzaru",
            "mood" => 4
        ];
        $m = Monkey::create($properties);
        return Response::json($m->get());
    }
}