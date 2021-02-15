<?php

chdir(__DIR__);

$loader = null;
if (file_exists('../vendor/autoload.php')) {
    $loader = include '../vendor/autoload.php';
} elseif (file_exists('../../../autoload.php')) {
    $loader = include '../../../autoload.php';
} else {
    throw new RuntimeException('vendor/autoload.php couuld not be found. Did you run `composer install`');
}

$loader->add('LmcUserApiToolsAuthTest', __DIR__);
