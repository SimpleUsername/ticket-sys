<?php
use application\core\Route;

spl_autoload_register(function ($class) {
    $filename = strtolower($class) . '.php';
    if(file_exists($filename)) {
        require_once $filename;
    }
});
Route::start(); // запускаем маршрутизатор
