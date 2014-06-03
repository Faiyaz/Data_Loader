<div class="row">&nbsp;</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <p class="lead text-center bg-info"><?php echo "$name.tsv"; ?></p>
    </div>
</div>

<table class='table table-bordered table-hover table-condensed table-responsive'>
    <tr>
    <th class="info">ID</th>
    <?php foreach ($columns as $key => $column): ?>
        <th class="info">
            <?php echo strtoupper($column); ?>
        </th>
    <?php endforeach ?>
    </tr>
    <?php for ($i=0; $i < count($rows); $i++): ?>
    <tr>
        <?php foreach ($rows[$i] as $key => $row): ?>
                <?php if ($key == 0): ?>
                    <?php 
                        $touch = strtoupper($row);
                        $stmt->bindParam(1, $touch);
                        $stmt->execute();
                        $data = $stmt->fetch(PDO::FETCH_OBJ);
                        if ($data) {
                            $good_tickers[$name][] = $touch;
                           echo "<td><span class='text-success'>" . $data->id . "</span></td>";
                           echo "<td><span class='text-success'>$touch</span></td>";
                        } else {
                            $bad_tickers[$name][] = $touch;
                            echo "<td class='danger'><span class='text-danger'>NOT FOUND</span></td>";
                            echo "<td class='danger'><span class='text-danger'>$touch</span></td>";
                        }
                    ?>
                <?php else: ?>
                     <?php echo "<td>$row</td>"; ?>
                <?php endif ?>
        <?php endforeach ?>
    </tr>
    <?php endfor ?>
</table>

<?php 

    $total_good = (isset($good_tickers[$name])) ? count($good_tickers[$name]) : '';
    $total_bad = (isset($bad_tickers[$name])) ? count($bad_tickers[$name]) : '';
?>
<div class="row">
    <div class="col-md-4 col-md-offset-8">
        <div class="alert alert-info">
            <p class="lead ">File process complete</p>
            <p><span class="glyphicon glyphicon-ok text-success"></span> File: <?php echo "$name.tsv"; ?></p>
            <p><span class="glyphicon glyphicon-ok text-success"></span> Total tickers: <?php echo $total_rows; ?></p>
            <?php if (!empty($total_good)): ?>
                <p><span class="glyphicon glyphicon-ok text-success"></span> Matched tickers: <?php echo $total_good; ?></p>
            <?php else: ?>
                <p><span class="glyphicon glyphicon-remove text-danger"></span> Matched tickers: 0</p>
            <?php endif ?>

            <?php if (!empty($total_bad)): ?>
                <p><span class="glyphicon glyphicon-remove text-danger"></span> Missing tickers: <?php echo $total_bad; ?></p>
            <?php else: ?>
                <p><span class="glyphicon glyphicon-ok text-success"></span> Missing tickers: 0</p>
            <?php endif ?>
        </div>
    </div>
</div>


