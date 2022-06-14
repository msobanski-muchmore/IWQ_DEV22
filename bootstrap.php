<?php

use App\Helper\PeselParser;
use App\Service\EmployeeService;
use App\Validator\PeselValidator;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Slim\Container;

require_once __DIR__ . '/vendor/autoload.php';


$container = new Container(require __DIR__ . '/settings.php');


$container[EntityManager::class] = function (Container $c): EntityManager {
    /** @var array $settings */
    $settings = $c->get('settings');

    $config = Setup::createConfiguration($settings['doctrine']['dev_mode']);
    $driver = new AnnotationDriver(new AnnotationReader(), $settings['doctrine']['metadata_dirs']);
    AnnotationRegistry::registerLoader('class_exists');
    $config->setMetadataDriverImpl($driver);

    return EntityManager::create($settings['doctrine']['connection'], $config);
};

$container[EmployeeService::class] = function (Container $c) {
    return new EmployeeService($c[EntityManager::class]);
};

$container[PeselValidator::class] = function(){
    return new PeselValidator();
};
$container[PeselParser::class] = function(){
    return new PeselParser();
};

return $container;
