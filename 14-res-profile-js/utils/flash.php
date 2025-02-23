<?php if (isset($_SESSION['error'])): ?>
  <div class="container-fluid">
    <div class="col-sm-offset-0 col-sm-6">
      <div class="panel panel-danger">
        <div class="panel-body text-danger bg-danger" style="border-radius: 3px">
          <?php
          echo $_SESSION['error'];
          unset($_SESSION['error']);
          ?>
        </div>
      </div>
    </div>
  </div>
<?php elseif (isset($_SESSION['success'])): ?>
  <div class="container-fluid">
    <div class="col-sm-offset-0 col-sm-6">
      <div class="panel panel-success">
        <div class="panel-body text-success bg-success" style="border-radius: 3px">
          <?php
          echo $_SESSION['success'];
          unset($_SESSION['success']);
          ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>