<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../app/config/config.php';
require_once '../app/core/Language.php';

Language::init();

require_once '../app/core/Database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Auth.php';
require_once '../app/core/Router.php';

$router = new Router();
$router->dispatch();