<?php
// Start  the session
session_start();

// Helpful redirect function
function redirect($location) {
    header("Location: $location");
    exit;
}

// Flatten asscociative arrays
function flatten(array $array) {
    $new_array = array();
    array_walk_recursive($array, function($a) use (&$new_array) { 
        $new_array[] = $a; 
    });

    return $new_array;
}

// File names MUST (===) equal/match the list of array 
$filenames_array = [
    "probability_loss_daily.tsv",
    "probability_loss_monthly.tsv",
    "probability_loss_weekly.tsv",
    "probability_win_daily.tsv",
    "probability_win_monthly.tsv",
    "probability_win_weekly.tsv",
    "zscore_abnormal.tsv",
    "zscore_all.tsv"
];

// Human readable file errors
$file_errors_array = [
    "File processed successfully.",
    "The processing file exceeds maximum file size allowed.",
    "The processing file exceeds the permitted size for this operation.",
    "The file was only partially processed.",
    "Nothing was done. Please select a valid file to process.",
    "Internal error, application missing a temporary folder.",
    "Failed to write file to disk.",
    "File processing has stopped by an unknown PHP extension."
];

// The column representation of the 'probability' table (minus the 'id', not needed since its auto increment)
$probability_columns = [
    "batch_id", "ticker_id", "prob", "avg_move", "std_dev", "signal_to_noise", "data_type_id", "up"
];

// The column representation of the 'zscore' table (minus the 'id', not needed since its auto increment)
$zscore_columns = [
    "batch_id", "ticker_id", "zscore", "abnormal"
];


// Validate, the name of the file matches our numeric pattern
function validate_name($file) {
    $zip_pattern = "/\d\d\d\d_\d\d_\d\d\.zip/";
    //$file_pattern = "/^(probability|zscore)(_[a-z]+)+\.tsv$/";
    if (preg_match($zip_pattern, $file)) {
        return true;
    }
    return false;
}

// Remove file extension
function file_name($value) {
    $array = explode(".", $value);
    return $array[0];
}

// Remove file extension, and also replace '_', with '-'
function batch_name($value) {
    $array = explode(".", $value);
    return str_replace("_", "-", $array[0]);
}

// Split the file name with '_', and get the table name value
function get_table($filename) { 
    $array = explode("_", $filename);
    return $array[0];
}

// Split the filename with '_', and get asking info 
function data_info($filename) { 
    $array = explode("_", $filename);
    if (get_table($filename) == 'probability') {
        $item['trend'] = ($array[1] === 'loss') ? 0 : 1;
        $item['type'] = $array[2];
        return $item;
    } else {
        $item['abnormal'] = ($array[1] === "abnormal") ? 1 : 0;
        return $item;
    }
}

// Get the number of columns from the file data
function column_amount($array) {
    // Check for values that has line ending
    $new_lines = preg_grep("/\n/", $array); // Sets an array with key => value
    // 0 based index, so adding 1 is necessary
    return min(array_keys($new_lines)) + 1; // Returns the minimum value key
}

// The actual engine, that sorts through the TSV data
function get_data($filename, $content, $type) {
    $sanitize = trim(preg_replace("/\n\d+/", "\n", $content));
    $tsv = explode("\t", $sanitize);
    $column_amount = column_amount($tsv);
    $total_tsv = count($tsv);
    $table = get_table($filename);
    // Using the file name, figure out the values
    if (isset(data_info($filename)['type'])) {
        $data_type = data_info($filename)['type'];
    } elseif (isset(data_info($filename)['abnormal'])) {
        $data_type = data_info($filename)['abnormal']; 
    }
    // Get column data ONLY
    if ($type == 'column') {
        for ($i=0; $i < $column_amount; $i++) {
            $headers[] = trim($tsv[$i]);
            $output = $headers;
        }
    }
    // Get all rows data ONLY
    if ($type == 'row') {
        for ($i=$column_amount; $i < $total_tsv; $i++) {
            $row_items[] = trim($tsv[$i]);
        }
        $split_rows = array_chunk($row_items, $column_amount);
        $output = $split_rows;
    }
    // Based on what $type is equal, return the appropriate result 
    return $output;
}

// Sorting arrays based on key
function set_columns_data($columns, $rows) {
    foreach ($columns as $key => $value) {
        if ($value === 'ticker' || $value === 'v1' ) {
            $output['ticker'] = array_column($rows, $key);
        } elseif ($value === 'prob') {
            $output['prob'] = array_column($rows, $key);
        } elseif ($value === 'avgmove') {
            $output['avgmove'] = array_column($rows, $key);
        } elseif ($value === 'stddev') {
            $output['stddev'] = array_column($rows, $key);
        } elseif ($value === 'signal_to_noise') {
            $output['signal_to_noise'] = array_column($rows, $key);
        } elseif ($value === 'zscore') {
            $output['zscore'] = array_column($rows, $key);
        }
    }
    return $output;
}

function breakdown(array $files, array $info) {
    if (count($files) == count($info)) {
        for ($i=0; $i < count($files); $i++) { 
            $new_array[$files[$i]] = $info[$i];
            $output = $new_array;
        }
    } else {
        $output = null;
    }
    return $output;
}
