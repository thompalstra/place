<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include(__DIR__."/fragments/autoloader.php");
$base = new \fragments\app\Application();
$exitCode = $base->run();
echo $exitCode;
?>
