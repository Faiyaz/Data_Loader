<?php 
$config = require '../config.php';

try {
    $dbh = new PDO("mysql:host={$config['host']};dbname={$config['name']}", $config['user'], $config['pw'], array(
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
    require 'sql.php';
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}