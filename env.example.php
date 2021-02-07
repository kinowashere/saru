<?php

function env($key)
{
    $app_env = [
        "db_name" => "saru",
        "db_user" => "",
        "db_port" => "3306",
        "db_host" => "",
        "db_pass" => "",
        "production" => true
    ];

    return $app_env[$key];
}