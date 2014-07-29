<?php

define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'ticket-sys');
define('DB_DRIVER', 'mysql');
define('SECURE_SALT', 'dataart');
define('TIME_ZONE', 'Europe/Kiev');

use com\lisa\entity;
use com\lisa\model;

spl_autoload_extensions(".php");
spl_autoload_register();

$sectorModel = new model\SectorModel();
print_r($sectorModel->getAllSectors());