<?
spl_autoload_register(function ($class) {
    $filename = __DIR__.DIRECTORY_SEPARATOR.preg_replace('/\\\/', DIRECTORY_SEPARATOR, strtolower($class)) . '.php';
    if(file_exists($filename)) {
        require_once $filename;
    }
});