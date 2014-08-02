<?php

// подключаем файлы ядра
require_once 'conf.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/MPDF56/mpdf.php';
spl_autoload_register(function ($class) {
    $filename = 'core/' . strtolower($class) . '.php';
    if(file_exists('application/'.$filename)) {
        require_once $filename;
    }
});
Route::start(); // запускаем маршрутизатор
