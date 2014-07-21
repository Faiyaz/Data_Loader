<?php if (isset($_SESSION['error_msg'])): ?>
    <div class="message">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div id="alert" class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $_SESSION['error_msg']; ?>
            <?php unset($_SESSION['error_msg']); ?>
          </div>
        </div>
      </div>
    </div>

<?php elseif (isset($_SESSION['success'])): ?>
    <div class="message">
      <div class="row">
        <div id="alert" class="col-md-6 col-md-offset-3">
          <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $_SESSION['success']; ?>
            <hr>
            <?php if (isset($_SESSION['log_error'])): ?>
              <span class="text-danger"><strong>Logging:</strong> <?php echo $_SESSION['log_error']; ?></span>
              <?php unset($_SESSION['log_error']); ?>
            <?php endif ?>

            <?php if (isset($_SESSION['log_success'])): ?>
              <span class="text-success"><strong>Logging:</strong> <?php echo $_SESSION['log_success']; ?></span>
              <?php unset($_SESSION['log_success']); ?>
            <?php endif ?>
            <?php unset($_SESSION['success']); ?>
          </div>
        </div>
      </div>
    </div>
<?php endif ?>
