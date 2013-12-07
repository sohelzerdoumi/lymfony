<?php
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationDriver;

require_once __DIR__.'/vendor/autoload.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__ . '/../entity');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__ . '/vendor/doctrine/');
$classLoader->register();
$configDoc = new \Doctrine\ORM\Configuration();
$configDoc->setProxyDir(__DIR__ . '/vendor/doctrine/Proxies/');
$configDoc->setProxyNamespace('Proxies');

#$configDoc->setAutoGenerateProxyClasses((APPLICATION_ENV == "development"));

AnnotationRegistry::registerFile(__DIR__."/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");
$reader = new AnnotationReader();
$driverImpl = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader, array(__DIR__ . '/../entity/'));
$driverImpl->getAllClassNames();
$configDoc->setMetadataDriverImpl($driverImpl);

//End of Changes

// if (APPLICATION_ENV == "development") {
//     $cache = new \Doctrine\Common\Cache\ArrayCache();
// } else {
   $cache = new \Doctrine\Common\Cache\ApcCache();
// }

$configDoc->setMetadataCacheImpl($cache);
$configDoc->setQueryCacheImpl($cache);

$em = \Doctrine\ORM\EntityManager::create($database, $configDoc);
$platform = $em->getConnection()->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('enum', 'string');

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));