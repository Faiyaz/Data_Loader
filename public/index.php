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
        <!-- Stylesheet -->
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>
        <div class="container">
            <div class="page-header">
                <h1 class="text-center">Data Loader <small>from .zip to DB</small></h1>
                <p class="text-center text-primary">Currently supports zip compressed TSV files ONLY</p>
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
        <!-- Custom Javascript -->
        <script>$('.message').delay("3500").slideUp("slow");</script>
    </body>
</html>
