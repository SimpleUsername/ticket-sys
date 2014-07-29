<?php

// подключаем файлы ядра
require_once 'conf.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/MPDF56/mpdf.php';
spl_autoload_register(function ($class) {
    require_once 'core/' . strtolower($class) . '.php';
});
Route::start(); // запускаем маршрутизатор
