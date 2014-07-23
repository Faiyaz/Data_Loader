<?php require_once 'include/header.php'; ?>
    <div class="page-header">
        <h1 class="text-center">Data Loader <small>from .zip to DB</small></h1>
        <p class="lead text-center text-info">Currently supports zip compressed TSV files ONLY</p>
        <p class="text-center">
            <a href="/data.php" class="text- active">Click here to view Database</a>
        </p>
    </div>

    <?php require_once 'message.php'; ?>

    <!-- FORM to get the TSV data -->
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-info">
          <div class="panel-heading">For Testing ONLY, please select Dry Run.</div>
            <div class="panel-body">
              <form class="form-inline" role="form" action="/" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="data">Please select a zip file</label>
                    <input type="file" id="data" name="data">
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" id="dry" value="dry" name="dry"> Dry run
                  </label>
                </div>
                <button type="submit" name="gsdata" value="load" class="btn btn-lg btn-primary">Load</button>
              </form>
            </div>
          </div>
      </div>
    </div>
    <?php require_once 'file_validation.php' ?>
<?php require_once 'include/footer.php'; ?>