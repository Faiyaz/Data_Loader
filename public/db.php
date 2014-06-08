<?php 
$config = require '../config.php';

try {
    $dbh = new PDO("mysql:host={$config['host']};dbname={$config['name']}", $config['user'], $config['pw'], array(
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_ERRMODE => $config['error']
        ));
    require 'data_validation.php';
} catch (PDOException $e) {
    $dbh->rollback();
    $_SESSION['error_msg'] =  "Error!: " . $e->getMessage() . "<br/>";
    redirect('/');
}