<?php

// подключаем файлы ядра
require_once 'conf.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/libs/MPDF56/mpdf.php';
require_once 'core/db.php';
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';
Route::start(); // запускаем маршрутизатор
