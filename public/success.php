<?php

$make_log = [];
$_SESSION['success'] = "<h4>Success!!!</h4>";
$_SESSION['success'] .= "<p><strong>$zip_name</strong> was successfully processed.</p><hr/>";
$_SESSION['success'] .= "<h5>Process breakdown:</h5>";
foreach (breakdown($good_files, $all_rows) as $key => $value) {
    $_SESSION['success'] .= "<p><span class='glyphicon glyphicon-ok text-success'></span> $key: <strong>$value</strong> rows</p>";
    $make_log['files'][] = ['name' => $key, 'total_rows' => $value, 'table' => get_table(file_name($key))];
}
$_SESSION['success'] .= "<hr/><h5>Summary:</h5>";
$_SESSION['success'] .= "<p>A total of <strong>" . array_sum($all_rows) . "</strong> rows of data were loaded into database.<br/>";
$_SESSION['success'] .= "<strong>" . count($p_vlu) . "</strong> rows for probability table.<br/>";
$_SESSION['success'] .= "<strong>" . count($z_vlu) . "</strong> rows for zscore table.</p>";


$make_log['process_info']['user_ip'] = getenv('REMOTE_ADDR') ? getenv('REMOTE_ADDR') : "unknown";
$make_log['process_info']['script'] = getenv('SCRIPT_FILENAME'); // SCRIPT FILENAME
$make_log['process_info']['batch'] = $batch_name;
$make_log['process_info']['zip_file'] = $zip_name;
$make_log['process_info']['added_on'] = $time;
$make_log['process_info']['total_rows'] = array_sum($all_rows);
$make_log['process_info']['probability_rows'] = count($p_vlu);
$make_log['process_info']['zscore_rows'] = count($z_vlu);

//var_dump($_SERVER);
$json_log = json_encode($make_log);
$jsonfile = "processed_" . file_name($zip_name) . ".json";

if (is_writable($config['LOG_DIR'])) {
    $files = scandir($config['LOG_DIR']);
    if (!in_array($jsonfile, $files)) {
        $handle = fopen($config['LOG_DIR'].$jsonfile, 'x');
        fwrite($handle, $json_log);
        fclose($handle);
        $_SESSION['log_success'] = "File <strong>$jsonfile</strong> created.";
    } else {
        $_SESSION['log_error'] = "A log file with the name <strong>$jsonfile</strong> already exist.";
    }
} else {
    $_SESSION['log_error'] = "Permission denied.";
}

redirect('/');
