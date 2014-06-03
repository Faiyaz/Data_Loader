<?php 
    // $name = strtolower(file_name($zip->getNameIndex($i)));
    // $content = strtolower($zip->getFromIndex($i));
    // $columns = get_data($name, $content, 'column'); 
    // $rows = get_data($name, $content, 'row');

// Check list
// 1. Verify that all Ticker names from the file already exists in the database 'ticker' table
// 2. Replace the file's Ticker names with its corresponding id from Ticker table
// 3. Insert the zip filename as yyyy-mm-dd into table 'Batch', and grab its id
// 4. Get the table name
// 5. Get the data type
// 6. Gather all the column data
// 7. Create the SQL Insert Statement

$stmt = $dbh->prepare("SELECT id FROM ticker where ticker_symbol = ?");
// Loop through each file and display the name and content
foreach ($f_i as $i) {
    $name = strtolower(file_name($zip->getNameIndex($i)));
    $content = strtolower($zip->getFromIndex($i));
    $columns = get_data($name, $content, 'column'); 
    $rows =  get_data($name, $content, 'row');
    $total_rows =  count($rows);

    // Table name
    $table =  get_table($name);
    // Column name
    $column =  $columns[0];

    require 'table.php';
}

