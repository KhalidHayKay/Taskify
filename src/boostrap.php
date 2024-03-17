<?php

require_once "vendor/autoload.php";
require "configs/filesPath.php";

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

return require CONFIG_PATH . '/container/container.php';
