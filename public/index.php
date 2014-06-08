<?php require_once 'helper.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data tester</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <style>
            /* Sticky footer styles
            -------------------------------------------------- */
            html {
              position: relative;
              min-height: 100%;
            }
            body {
              /* Margin bottom by footer height */
              margin-bottom: 60px;
            }
            #footer {
              position: absolute;
              bottom: 0;
              width: 100%;
              /* Set the fixed height of the footer here */
              height: 60px;
              background-color: #f5f5f5;
            }

            .container {
              width: auto;
              max-width: 970px;
              padding: 0 15px;
            }
            .container .text-muted {
              margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="page-header">
                <h1 class="text-center">Data Loader <small>from .zip to DB</small></h1>
                <p class="text-center text-primary">Currently supports zip compressed TSV files ONLY</p>
            </div>
            <!-- FORM to get the TSV data -->
            <div class="row">
              <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-info">
                  <div class="panel-heading">Dry run is default. To commit, box must be unchecked.</div>
                    <div class="panel-body">
                      <form class="form-inline" role="form" action="/" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="data">Please select a zip file</label>
                            <input type="file" id="data" name="data">
                        </div>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" id="dry" value="dry" name="dry" checked="checked"> Dry run
                          </label>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary">Load</button>
                      </form>
                    </div>
                  </div>
              </div>
            </div>
            <?php require_once 'file_validation.php' ?>
            <?php if (isset($_SESSION['error_msg'])): ?>
              <div class="row">
                <div class="col-md-6 col-md-offset-3">
                  <div id="alert" class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p><?php echo $_SESSION['error_msg']; ?></p>
                    <?php unset($_SESSION['error_msg']); ?>
                  </div>
                </div>
              </div>
            <?php endif ?>

            <?php if (isset($_SESSION['success'])): ?>
              <div class="row">
                <div id="alert" class="col-md-6 col-md-offset-3">
                  <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p><?php echo $_SESSION['success']; ?></p>
                    <hr>
                    <?php if (isset($_SESSION['log_error'])): ?>
                      <p class="text-danger"><strong>Logging:</strong> <?php echo $_SESSION['log_error']; ?></p>
                      <?php unset($_SESSION['log_error']); ?>
                    <?php endif ?>

                    <?php if (isset($_SESSION['log_success'])): ?>
                      <p class="text-success"><strong>Logging:</strong> <?php echo $_SESSION['log_success']; ?></p>
                      <?php unset($_SESSION['log_success']); ?>
                    <?php endif ?>
                    <?php unset($_SESSION['success']); ?>
                  </div>
                </div>
              </div>
            <?php endif ?>
        </div>

        <div id="footer">
            <div class="container">
                <p class="text-muted">Copyright &copy; <?php echo date('Y'); ?> Faiyaz Haider</p>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    </body>
</html>
