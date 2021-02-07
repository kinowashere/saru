<?php
spl_autoload_register(function ($class) {
    /** @var array $vendor_packages Packages to be included from vendor/ */
    $vendor_packages = ["Saru"];

    $filenameForwardSlash = "/".str_replace("\\", "/", $class).".php";

    $nameNamespace = explode("\\", $class)[0];
    if(in_array($nameNamespace, $vendor_packages)) {
        $filenameForwardSlash = "/vendor".$filenameForwardSlash;
    }

    include_once __DIR__.$filenameForwardSlash;
});
