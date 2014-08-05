<?php
if (extension_loaded("xhprof")) {
    xhprof_enable();
}

//error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'autoloader.php';
date_default_timezone_set(Conf::TIME_ZONE);
require_once 'application/bootstrap.php';

if (extension_loaded("xhprof")) {
    $xhprof_data = xhprof_disable();
    include_once "/xhprof_lib/config.php";
    include_once "/xhprof_lib/utils/xhprof_lib.php";
    include_once "/xhprof_lib/utils/xhprof_runs.php";
    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, preg_replace('#(&|/)#', '_', $_SERVER['REQUEST_URI']));
}
