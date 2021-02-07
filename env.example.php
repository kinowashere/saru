<?php

function env($key)
{
    /** @var array $app_env All Application environment variables in an array*/
    $app_env = [
        "db_name" => "scandiweb",
        "db_user" => "",
        "db_port" => "3306",
        "db_host" => "",
        "db_pass" => "",
        "production" => true
    ];

    return $app_env[$key];
}