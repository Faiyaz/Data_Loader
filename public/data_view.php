<?php
require 'public/db.php';

$stmt_get_batches = $dbh->query("SELECT name, created_at FROM batch");
$stmt_get_batches->execute(); // Execute the SQL
// Fetch the result. If matched, ID obj will be returned, else false will be returned
$result_objs = $stmt_get_batches->fetchAll(PDO::FETCH_OBJ);
$batch_total = count($result_objs);
?>

<?php if ($result_objs): ?>
    <div class="row">&nbsp;</div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <p class="lead text-center bg-info">Batch <small>( <?php echo $batch_total; ?> )</small></p>
        </div>
    </div>

    <table class='table table-bordered table-hover table-condensed table-responsive'>
        <tr>
            <th class="info">
                Name
            </th>
            <th class="info">
                Check log
            </th>
        </tr>
        <?php foreach ($result_objs as $batch): ?>
            <tr>
                <td><?php echo $batch->name; ?></td>
                <td>
                    <p>
                        <a href="#" class="null">
                            Click to Toggle view
                        </a>
                    </p>

                    <div class="collapse" >
                        <?php
                        if (file_exists($filename = "log/processed_" . return_name($batch->name) . ".json"))
                        {
                            $log_objs = json_decode(file_get_contents("log/processed_" . return_name($batch->name) . ".json"));
                            $files_objs = $log_objs->files;
                            $process_info_obj = $log_objs->process_info;
                            $total_files_objs = count($files_objs);

                            //var_dump($process_info_obj);
                            echo "<ul class='list-unstyled'>";
                                echo "<li>Submitted by IP: <strong class='text-primary'>$process_info_obj->user_ip</strong></li>";
                                echo "<li>Batch added on: <strong class='text-primary'>$process_info_obj->added_on</strong></li>";
                                echo "<li>Total files submitted: <strong class='text-primary'>$total_files_objs</strong></li>";
                                echo "<li><ul>";
                                foreach ($files_objs as $file) {
                                    echo "<li>File <strong class='text-primary'>" . $file->name . "</strong> with <strong class='text-primary'>{$file->total_rows}</strong> rows of data</li>";
                                }
                                echo "</li></ul>";
                                echo "<li>Total rows of data for zscore table: <strong class='text-primary'>" . $process_info_obj->zscore_rows . "</strong></li>";
                                echo "<li>Total rows of data for probability table: <strong class='text-primary'>" . $process_info_obj->probability_rows . "</strong></li>";
                                echo "<li>Total rows of data copied: <strong class='text-primary'>" . $process_info_obj->total_rows . "</strong></li>";
                            echo "</ul>";
                        } else {
                            echo "Unable to locate a log file";
                        }
                        ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <p class="lead text-center bg-info">Database is empty</p>
        </div>
    </div>
<?php endif; ?>
