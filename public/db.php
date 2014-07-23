<?php

try {
    $dbh = new PDO("mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']}", $config['DB_USER'], $config['DB_PW'], $config['DB_OPTION']);
    if (isset($option_db))
    {
        require "{$option_db}.php";
    }

} catch (PDOException $e) {
    if (isset($commit_fail) and $commit_fail) {
        $dbh->rollback();
    }
    $_SESSION['error_msg'] =  "Error!: " . $e->getMessage() . "<br/>";
    redirect('/');
}
