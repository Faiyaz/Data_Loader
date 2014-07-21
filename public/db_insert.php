<?php
// 1. Start the transaction
$dbh->beginTransaction();

// 2. Insert the batch name (zip file name = 'date')
$stmt_insert_batch = $dbh->prepare("INSERT INTO `{$config['DB_NAME']}`.batch (name, created_at, updated_at) VALUES (?,?,?)");
$stmt_insert_batch->bindParam(1, $batch_name); // bind the parameter with the zip name value
$stmt_insert_batch->bindParam(2, $time); // bind the parameter with current time
$stmt_insert_batch->bindParam(3, $time); // bind the parameter with current time
$stmt_insert_batch->execute(); // Execute the insert statement
$last_insert_batch_id = $dbh->lastInsertId(); // get the id

// 3. Set the value arguements based on number columns
$p_qs = array_fill(0, count($probability_columns), '?'); // Probability table
$z_qs = array_fill(0, count($zscore_columns), '?'); // Zscore table

// 4. Set the data values based on table columns
foreach ($p_vlu as $value) { // Probability table
    array_unshift($value, $last_insert_batch_id); // Prepend the batch id
    $probability_values[] = $value;
}

foreach ($z_vlu as $value) { // Zscore table
    array_unshift($value, $last_insert_batch_id); // Prepend the batch id
    $zscore_values[] = $value;
}

// 5. Create the INSERT statements
$p_sql = "INSERT INTO `{$config['DB_NAME']}`.probability (" . implode(', ', $probability_columns) . ") VALUES (" . implode(', ', $p_qs) . ")";
$z_sql = "INSERT INTO `{$config['DB_NAME']}`.zscore (" . implode(', ', $zscore_columns) . ") VALUES (" . implode(', ', $z_qs) . ")";
$stmt_insert_probability = $dbh->prepare($p_sql);
$stmt_insert_zscore = $dbh->prepare($z_sql);

// 6. Execute the queries
foreach ($probability_values as $value) { // Probability table
    $stmt_insert_probability->execute($value);
}

foreach ($zscore_values as $value) { // Zscore table
    $stmt_insert_zscore->execute($value);
}

// 8. Commit the transaction
if ($dbh->commit()) {
    require 'success.php';
}
