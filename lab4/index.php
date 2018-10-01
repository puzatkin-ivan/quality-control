<?php

require_once __DIR__ . '/src/Main.class.php';

try
{
    $main = new Main($argc, $argv);
    $main->run();
}
catch (Exception $exception)
{
    echo (string)$exception;
}