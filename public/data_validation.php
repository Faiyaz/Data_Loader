<?php
// Set the batch name
$batch_name = batch_name($zip_name);

// Check if the batch (zip file name), already exists in the database
$stmt_check_batch = $dbh->prepare("SELECT id, name FROM batch where name = ?");
$stmt_check_batch->bindParam(1, $batch_name); // bind it with the where clause
$stmt_check_batch->execute(); // Execute the SQL
// Fetch the result. If matched, ID obj will be returned, else false will be returned
$result_obj = $stmt_check_batch->fetch(PDO::FETCH_OBJ);

// If found, we already have the data on file, save error, and exit
if ($result_obj) {
    $_SESSION['error_msg'] = "<strong>$zip_name</strong> is a possible duplicate. ";
    $_SESSION['error_msg'] .= "The batch id of <strong>". $result_obj->id . "</strong> with the name <strong>" . $result_obj->name;
    $_SESSION['error_msg'] .= "</strong> already exist in the database.";
    redirect('/');
} else { // no match found, we have new data. proceed to next stage
    // 1. Verify if all data tickers exists in the database

    // Check if all the ticker symbol from all the data file exists in the ticker table
    $stmt_check_ticker = $dbh->prepare("SELECT id FROM ticker where symbol = ?");

    // Get Data Type daily, weekly, monthly (only for Probability table)
    $stmt_get_data_type_id = $dbh->prepare("SELECT id FROM data_type where name = ?");

    // Loop through each file get each of their name and content
    foreach ($f_i as $i) {
        // TSV File name
        $name = strtolower(file_name($zip->getNameIndex($i))); // String
        // Table name for the data: 'probability' / 'zscore'
        $table =  get_table($name); // String
        // Data type (for table 'probability' only)
        if ($table === 'probability') {
            $bind = data_info($name)['type'];
            $stmt_get_data_type_id->bindParam(1, $bind);
            $stmt_get_data_type_id->execute();
            $type_obj = $stmt_get_data_type_id->fetch(PDO::FETCH_OBJ);
            $type = $type_obj->id;
        }

        // Is abnormal (for table 'zscore' only)
        $abnormal = (isset(data_info($name)['abnormal'])) ? data_info($name)['abnormal'] : null;
        // Down trend or up trend (only for probability table)
        $up = (isset(data_info($name)['trend'])) ? data_info($name)['trend'] : null;
        // Grab the unprocessed string of file content
        $content = strtolower($zip->getFromIndex($i)); // String
        // Process the content, get only the column headers into an array
        $columns = get_data($name, $content, 'column'); // Array


        $db_columns = ($table === 'probability') ? $probability_columns: $zscore_columns;
        // Process the content, get all the datas and store them as multidimensional array (one array per row)
        $rows =  get_data($name, $content, 'row'); // Array
        // Count the total number of arrays (rows of data) per file
        $rows_per_file =  count($rows); // Int

        // Grab all the rows to add later for total
        $all_rows[] = count($rows); //int

        // Set the root data array of all columns;
        $clmn = set_columns_data($columns, $rows); // Array

        // Loop through all rows per file
        for ($i=0; $i < $rows_per_file; $i++) {
            $bind = strtoupper($clmn['ticker'][$i]);
            $stmt_check_ticker->bindParam(1, $bind);
            $stmt_check_ticker->execute();
            $ticker = $stmt_check_ticker->fetch(PDO::FETCH_OBJ);
            if ($ticker) {
                $ticker_id = $ticker->id;
                $tickers_m = (!isset($tickers_m)) ? [] : $tickers_m;
                array_push($tickers_m, $clmn['ticker'][$i]);

                // Setup the contents for SQL INSERT
                if ($table == 'probability') {
                    $p_vlu[] = [
                        $ticker_id,
                        $clmn['prob'][$i],
                        $clmn['avgmove'][$i],
                        $clmn['stddev'][$i],
                        $clmn['signal_to_noise'][$i],
                        $type,
                        $up
                    ];
                } else { // 'zscore' table
                    $z_vlu[] = [
                        $ticker_id,
                        $clmn['zscore'][$i],
                        $abnormal
                    ];
                }
            } else {
                $tickers_um = (!isset($tickers_um)) ? [] : $tickers_um;
                array_push($tickers_um, $clmn['ticker'][$i]);
            }
        }

        // Turns on when Dry run is set
        if (isset($_POST['dry']) && $_POST['dry'] == 'dry') {
            require 'table.php';
        }
    }
}

//var_dump($z_vlu);

// Only works, when not in dry run
if (!isset($_POST['dry'])) {
    // Compare the total number of rows vs total number of matched rows
    if ( isset($tickers_m) and array_sum($all_rows) == (count($tickers_m)) ) {
        require 'db_insert.php';

    } elseif ( isset($tickers_um) ) {
        $_SESSION['error_msg'] = "FAIL! A total of " . count($tickers_um) . " unidentified Tickers found<hr/>";
        $_SESSION['error_msg'] .= strtoupper(implode(", ", (array_unique($tickers_um))));
        redirect('/');
    }
}
