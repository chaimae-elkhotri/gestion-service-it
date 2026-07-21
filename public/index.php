<?php

session_start();

require_once '../app/config/config.php';
require_once '../app/core/Database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Auth.php';
require_once '../app/core/Router.php';

$router = new Router();
$router->dispatch();