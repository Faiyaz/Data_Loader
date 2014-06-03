<?php
// Get the file content into a string
$sp500_full_ticker_list_raw =  file_get_contents('../data/SP500_full_ticker_list_minified.json');
// Get the file content into a string
$sp500_full_ticker_list = json_decode($sp500_full_ticker_list_raw, true);
// Count the total items
$total_tickers = count($sp500_full_ticker_list);
// Loop through the data array,  
for ($i=0; $i < $total_tickers; $i++) {
    // for each nested array, pull only the needed value
    foreach ($sp500_full_ticker_list[$i] as $key => $value) {
        if ($key == "Ticker symbol" || $key == "CIK" || $key == "Security") {
            //make a new array

            if ($key == "CIK") {
                $key = "cik";
                $new_array[$i][$key] = $value;                
            }
            if ($key == "Ticker symbol") {
                $key = "ticker_symbol";
                $new_array[$i][$key] = $value;
            }

            if ($key == "Security") {
                $key = "full_name";
                $new_array[$i][$key] = $value;                
            }
        }
    }
}
//var_dump($new_array);

$new_json = json_encode($new_array);

print_r($new_json);

// Database specific
$db = "GS_Test"; // DB
$table = "ticker"; // Table
$columns = "cik, ticker_symbol, full_name"; // Columns to insert

// Create the sql INSERT statement
$sql_query = "INSERT INTO $db.$table ($columns) VALUES ";

for ($i=0; $i < $total_tickers; $i++) {
    $cik = $new_array[$i]['cik'];
    $ticker_symbol = $new_array[$i]['ticker_symbol'];
    $full_name = $new_array[$i]['full_name'];

    $sql_query .= "(\"$cik\", \"$ticker_symbol\", \"$full_name\")";
    ($i < $total_tickers - 1) ? $sql_query .= ", " : $sql_query .= ";";
}

//echo $sql_query;
