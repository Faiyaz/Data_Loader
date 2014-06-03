<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="alert alert-info">
            <p><span class="glyphicon glyphicon-ok text-success"></span> Zip file: <?php echo $zip_name; ?></p>
            <p>TSV files: <?php echo count($good_files); ?></p>
            <ul class="list-unstyled">
            <?php foreach ($good_files as $key => $value): ?>
                <li><span class="glyphicon glyphicon-ok text-success"></span> <?php echo $value; ?></li>
            <?php endforeach ?>
            </ul>
        </div>
            
    </div>
</div>
