<?php

require 'modules/auth.php';
require_once 'modules/pdo.php';

if (isset($_POST['cancel'])) {
  header('Location: index.php');
  exit();
}

if (isset($_POST['add'])) {
  if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
    $_SESSION['error'] = 'All fields are required';
  } else if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
    $_SESSION['error'] = 'Mileage and year must be numeric';
  } else {
    try {
      $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage) VALUES (:mk, :md, :yr, :mi)');
      $stmt->execute([
        ':mk' => $_POST['make'],
        ':md' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage'],
      ]);
      $_SESSION['success'] = 'Record added';
    } catch (Exception $ex) {
      error_log("autos.php, SQL error=" . $ex->getMessage());
      $_SESSION['error'] = 'Internal server error, please contact support';
    }
  }
  // A convenient way to stay on the same page, show a message, 
  // and prevent re-submission on reload is to combine the 
  // PRG (Post/Redirect/Get) pattern with a self-redirect
  if (isset($_SESSION['error'])) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
  } else if ($_SESSION['success']) {
    header('Location: index.php');
    exit();
  }
}

////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html lang="en">

<?php require 'modules/head.php'; ?>

<body>
  <div class="container">
    <h1>
      Tracking Autos for
      <code class="text-muted">
        <?= htmlentities($_SESSION['name']) ?>
      </code>
    </h1>
    <br />

    <?php if (isset($_SESSION['error'])): ?>
      <div class="container-fluid" style="padding-left: 0">
        <div class="col-sm-offset-2 col-sm-4">
          <div class="panel panel-danger">
            <div class="panel-body text-danger bg-danger" style="border-radius: 3px">
              <?php
              echo $_SESSION['error'];
              // Flash the error message to prevent it 
              // from showing up on further page reloads
              unset($_SESSION['error']);
              ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <form method="post" class="form-horizontal">
      <div class="form-group">
        <label for="make" class="col-sm-2 control-label">Make:</label>
        <div class="col-sm-4">
          <input type="text" name="make" id="make" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="model" class="col-sm-2 control-label">Model:</label>
        <div class="col-sm-4">
          <input type="text" name="model" id="model" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="year" class="col-sm-2 control-label">Year:</label>
        <div class="col-sm-2">
          <input type="text" name="year" id="year" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="mileage" class="col-sm-2 control-label">Mileage:</label>
        <div class="col-sm-2">
          <input type="text" name="mileage" id="mileage" class="form-control">
        </div>
      </div>
      <br />
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-3">
          <input type="submit" name="add" value="Add" class="btn btn-primary">
          <input type="submit" name="cancel" value="Cancel" class="btn btn-default">
        </div>
      </div>
    </form>
  </div>
</body>

</html>