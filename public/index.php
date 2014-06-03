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
                <h1 class="text-center">Data tester <small>from .zip to DB</small></h1>
            </div>
            <!-- FORM to get the TSV data -->
            <div class="row">
              <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-info">
                  <div class="panel-heading">Please test with zipped TSV files</div>
                    <div class="panel-body">
                      <form class="form-inline" role="form" action="/" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="data">.zip extension only</label>
                            <input type="file" id="data" name="data">
                        </div>
                        <div class="pull-right">
                          <button type="submit" class="btn btn-lg btn-primary">Run test</button>
                        </div>
                      </form>
                    </div>
                  </div>
              </div>
            </div>
            <?php require_once 'file_validation.php' ?>
            <?php if (isset($_SESSION['error_msg'])): ?>
              <div class="row">
                <div class="col-md-6 col-md-offset-3">
                  <div class="alert alert-danger">
                    <p><?php echo $_SESSION['error_msg']; ?></p>
                    <?php unset($_SESSION['error_msg']); ?>
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
