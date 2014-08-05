<?php
use application\core\Route;

spl_autoload_register(function ($class) {
    $filename = preg_replace('/\\\/', DIRECTORY_SEPARATOR, strtolower($class)) . '.php';
    if(file_exists($filename)) {
        require_once $filename;
    }
});
Route::start(); // запускаем маршрутизатор
