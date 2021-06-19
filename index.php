<?php

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
require './vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(BASE_PATH);
$dotenv->load();

include './app/Function.php';
include './app/Index.php';

$action = $_GET['action']?:'items';
$index = new Index();
$index->{$action}($_POST);
