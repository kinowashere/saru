<?php

require_once '../env.php';

ob_start();

$urls = include ('../urls.php');

if(!env("production"))
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
