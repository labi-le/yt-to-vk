<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;

require_once "vendor/autoload.php";

//$config = Setup::createAnnotationMetadataConfiguration(["./App/Entity"]);
$config = Setup::createAnnotationMetadataConfiguration([getenv("ENTITY_PATH")]);
//$connection =
//    [
//        "driver" => "pdo_pgsql",
//
//        "host" => "0.0.0.0",
//        "dbname" => "app",
//        "user" => "app",
//        "password" => "Usy27tUxTZDgBbYv3JvCpdySUVKEMbvU7d2R55msuQfLNGD8hj"
//    ];

$connection =
    [
        "driver" => getenv("DATABASE_DRIVER"),

        "host" => getenv("DATABASE_HOST"),
        "dbname" => getenv("DATABASE_NAME"),
        "user" => getenv("DATABASE_USER"),
        "password" => getenv("DATABASE_PASSWORD")
    ];

$entityManager = EntityManager::create($connection, $config);

return ConsoleRunner::createHelperSet($entityManager);