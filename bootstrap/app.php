<?php

use Astaroth\Foundation\Application;

const UPLOAD_GROUP = 190405359;

$app = new Application();
$app->run(dirname(__DIR__));

//if prod set
// $app->run(type: Application::PRODUCTION);
