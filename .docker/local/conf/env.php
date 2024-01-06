<?php
error_reporting(E_ALL & ~E_NOTICE);
$env = $_SERVER;
$path = '/etc/php/8.2/fpm/env.conf';

// Verifica a existência do arquivo config
if (file_exists($path)) {
    unlink($path);
}

// Cria a escreve no config
$file = fopen($path, "w");
foreach ($env as $k => $value) {
    if($value != 'null' && $value != '' && $k != 'argv'){
       $str = "env[{$k}]=\"{$value}\"\n";
       fwrite($file, $str);
    }
}
fclose($file);

