<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/db.php';

$app = AppFactory::create();
$app->setBasePath("/slimapp/api");
//Courses Routes
require "../src/routes/courses.php";

$app->run();