<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;

require_once "vendor/autoload.php";

$config = Setup::createAnnotationMetadataConfiguration(["./App/Entity"], true);

$connection =
    [
        "driver" => "pdo_mysql",

        "host" => "141.8.193.236",
        "dbname" => "f0573038_test",
        "user" => "f0573038_test",
        "password" => "9387"
    ];

$entityManager = EntityManager::create($connection, $config);

return ConsoleRunner::createHelperSet($entityManager);