<?php
use application\core\Route;
// подключаем файлы ядра
//require_once $_SERVER['DOCUMENT_ROOT'].'/libs/MPDF56/mpdf.php';
spl_autoload_register(function ($class) {
    $filename = strtolower($class) . '.php';
    if(file_exists($filename)) {
        require_once $filename;
    }
});
Route::start(); // запускаем маршрутизатор
