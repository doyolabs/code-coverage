<?php

$autoloadFile = __DIR__.'/../../../vendor/autoload.php';
if(is_file($test = __DIR__.'/../../../../../vendor/autoload.php')){
    $autoloadFile = $test;
}

include $autoloadFile;
