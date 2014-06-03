<?php
// Start session
session_start();

function redirect($location) {
    header("Location: $location");
    exit;
}

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

function file_name($value) {
    $array = explode(".", $value);
    return $array[0];
}

function validate_name($file) {
    $zip_pattern = "/\d\d\d\d_\d\d_\d\d\.zip/";
    //$file_pattern = "/^(probability|zscore)(_[a-z]+)+\.tsv$/";
    if (preg_match($zip_pattern, $file)) {
        return true;
    }
    return false;
}


function get_table($filename) { 
    $array = explode("_", $filename);
    return $array[0];
}

function data_info($filename) { 
    $array = explode("_", $filename);
    if (get_table($filename) == 'probability') {
        $item['trend'] = $array[1];
        $item['type'] = $array[2];
        return $item;
    } else {
        $item['type'] = $array[1];
        return $item;
    }
}

function get_data($filename, $content, $type) {
    $tsv = explode("\t", trim($content));
    $total_tsv = count($tsv);
    $table = get_table($filename);
    $data_type = data_info($filename)['type'];

    if ($table == 'probability') {
        if ($data_type == 'daily' || $data_type == 'weekly') {
            $column_items = 6;
        } elseif ($data_type == 'monthly') {
            $column_items = 5;
        }
    } elseif ($table == 'zscore') {
        $column_items = 2;
    }

    if ($type == 'column') {
        for ($i=0; $i < $column_items; $i++) {
            if ($i == $column_items - 1) {
                $part = explode("\n", $tsv[$i]);
                $headers[] = $part[0];
            } else {
                $headers[] = $tsv[$i];
            }
            $output = $headers;
        }
    }
    
    if ($type == 'row') {
        for ($i=$column_items; $i < $total_tsv; $i++) {
            if (($i + 1) % $column_items == 0) {
                $part = explode("\n", $tsv[$i]);
                $row_items[] = $part[0];
            } else {
                $row_items[] = $tsv[$i];
            }
        }

        $split_rows = array_chunk($row_items, $column_items);

        $output = $split_rows;
    }

    return $output;
}

