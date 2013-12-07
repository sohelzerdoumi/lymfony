<?php

define('BASE_CMS', TRUE);

// cli-config.php
require_once __DIR__."/config.php";
require_once "bootstrap.php";

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));