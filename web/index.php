<?php 
/**
 * Entry point
 * @author Sohel Zerdoumi <sohel.zerdoumi@gmail.com>
 */
error_reporting(E_ALL);
ini_set('display_errors',1);


define('BASE_CMS', TRUE);


include __DIR__.'/../kernel/include/functions.php';
include __DIR__.'/../kernel/include/config.php';
include __DIR__.'/../kernel/include/database.php';
include __DIR__.'/../kernel/include/loader.php';

header('Content-Type: text/html; charset=UTF-8');
session_start();
include __DIR__.'/../kernel/include/Router/Router.php';

$router = new Router($uri);
$router->run();

?>
