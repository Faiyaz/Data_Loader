<div class="row">&nbsp;</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <p class="lead text-center bg-info"><?php echo "$name.tsv"; ?></p>
    </div>
</div>

<table class='table table-bordered table-hover table-condensed table-responsive'>
    <tr>
        <?php foreach ($db_columns as $key => $column): ?>
            <th class="info">
                <?php echo strtoupper($column); ?>
            </th>
        <?php endforeach ?>
    </tr>
    <?php for ($i=0; $i < $rows_per_file; $i++): ?>
        <?php 
            $bind = strtoupper($clmn['ticker'][$i]);
            $stmt_check_ticker->bindParam(1, $bind);
            $stmt_check_ticker->execute();
            $ticker = $stmt_check_ticker->fetch(PDO::FETCH_OBJ); 
        ?>
        <tr <?php echo (!$ticker) ? "class='danger'" : null;?>>
            <td><?php echo $batch_name; ?></td>
            <?php if ($ticker): ?>
                <?php $matched_tickers1[$name][] = $clmn['ticker'][$i]; ?>
                <td><span class='text-success'><?php echo $ticker->id; ?></span></td>
            <?php else: ?>
                <?php $unmatched_tickers1[$name][] = $clmn['ticker'][$i]; ?>
                <td><span class='text-danger'><?php echo strtoupper($clmn['ticker'][$i]); ?></span></td>
            <?php endif ?>
            <?php if ($table == 'probability'): ?>
                <td><?php echo $clmn['prob'][$i]; ?></td>
                <td><?php echo $clmn['avgmove'][$i]; ?></td>
                <td><?php echo $clmn['stddev'][$i]; ?></td>
                <td><?php echo $clmn['signal_to_noise'][$i]; ?></td>
                <td><?php echo $type; ?></td>
                <td><?php echo $up; ?></td>
            <?php else: ?>
                <td><?php echo $clmn['zscore'][$i]; ?></td>
                <td><?php echo $abnormal; ?></td>
            <?php endif ?>
        </tr>
    <?php endfor ?>
</table>

<?php
    $matched = (isset($matched_tickers1[$name])) ? $matched_tickers1[$name] : '';
    $unmatched = (isset($unmatched_tickers1[$name])) ? $unmatched_tickers1[$name] : '';
?>
<div class="row">
    <div class="col-md-4 col-md-offset-8">
        <div class="alert alert-info">
            <p class="lead ">File process complete</p>
            <p><span class="glyphicon glyphicon-ok text-success"></span> File: <?php echo "$name.tsv"; ?></p>
            <p><span class="glyphicon glyphicon-ok text-success"></span> Total tickers: <?php echo $rows_per_file; ?></p>
            <?php if (!empty($matched)): ?>
                <p><span class="glyphicon glyphicon-ok text-success"></span> Matched tickers: <?php echo count($matched); ?></p>
            <?php else: ?>
                <p><span class="glyphicon glyphicon-remove text-danger"></span> Matched tickers: 0</p>
            <?php endif ?>

            <?php if (!empty($unmatched)): ?>
                <p><span class="glyphicon glyphicon-remove text-danger"></span> Missing tickers: <?php echo count($unmatched); ?></p>
            <?php else: ?>
                <p><span class="glyphicon glyphicon-ok text-success"></span> Missing tickers: 0</p>
            <?php endif ?>
        </div>
    </div>
</div>
