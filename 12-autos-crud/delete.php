<?php

require 'modules/auth.php';
require_once 'modules/pdo.php';

$row = null;

if (isset($_GET['auto_id'])) {
  $stmt = $pdo->prepare('SELECT auto_id, make, model, year, mileage FROM autos WHERE auto_id = :id');
  $stmt->execute([':id' => $_GET['auto_id']]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$row) {
  $_SESSION['error'] = 'Bad value for ID';
  header('Location: index.php');
  exit();
}

if (isset($_POST['cancel'])) {
  header('Location: index.php');
  exit();
}

if (isset($_POST['delete']) && isset($_POST['auto_id'])) {
  try {
    $stmt = $pdo->prepare('DELETE FROM autos WHERE auto_id=:id');
    $stmt->execute([':id' => $_POST['auto_id']]);
    $_SESSION['success'] = 'Record deleted';
  } catch (Exception $ex) {
    error_log("autos.php, SQL error=" . $ex->getMessage());
    $_SESSION['error'] = 'Internal server error, please contact support';
  }
  // A convenient way to stay on the same page, show a message, 
  // and prevent re-submission on reload is to combine the 
  // PRG (Post/Redirect/Get) pattern with a self-redirect
  if (isset($_SESSION['error'])) {
    header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
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
      <input type="hidden" name="auto_id" id="auto_id" class="form-control" value="<?= $row['auto_id'] ?>" readonly>
      <div class="form-group">
        <label for="make" class="col-sm-2 control-label">Make:</label>
        <div class="col-sm-4">
          <input type="text" name="make" id="make" class="form-control" value="<?= htmlentities($row['make']) ?>"
            readonly>
        </div>
      </div>
      <div class="form-group">
        <label for="model" class="col-sm-2 control-label">Model:</label>
        <div class="col-sm-4">
          <input type="text" name="model" id="model" class="form-control" value="<?= htmlentities($row['model']) ?>"
            readonly>
        </div>
      </div>
      <div class="form-group">
        <label for="year" class="col-sm-2 control-label">Year:</label>
        <div class="col-sm-2">
          <input type="text" name="year" id="year" class="form-control" value="<?= htmlentities($row['year']) ?>"
            readonly>
        </div>
      </div>
      <div class="form-group">
        <label for="mileage" class="col-sm-2 control-label">Mileage:</label>
        <div class="col-sm-2">
          <input type="text" name="mileage" id="mileage" class="form-control"
            value="<?= htmlentities($row['mileage']) ?>" readonly>
        </div>
      </div>
      <br />
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-3">
          <input type="submit" name="delete" value="Delete" class="btn btn-danger">
          <input type="submit" name="cancel" value="Cancel" class="btn btn-default">
        </div>
      </div>
    </form>
  </div>
</body>

</html>