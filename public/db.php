<?php
$config = require '../config.php';

try {
    $dbh = new PDO("mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']}", $config['DB_USER'], $config['DB_PW'], $config['DB_OPTION']);
    require 'data_validation.php';
} catch (PDOException $e) {
    $dbh->rollback();
    $_SESSION['error_msg'] =  "Error!: " . $e->getMessage() . "<br/>";
    redirect('/');
}
