<?php

require 'modules/auth.php';
require_once 'modules/pdo.php';

if (isset($_POST['logout'])) {
  header('Location: logout.php');
  exit();
}

if (isset($_POST['add']) && isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
  if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
    $_SESSION['error'] = 'Mileage and year must be numeric';
  } else if (strlen($_POST['make']) < 1) {
    $_SESSION['error'] = 'Make is required';
  } else {
    try {
      $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)');
      $stmt->execute([
        ':mk' => $_POST['make'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage'],
      ]);
      $_SESSION['success'] = 'Record inserted';
    } catch (Exception $ex) {
      error_log("autos.php, SQL error=" . $ex->getMessage());
      $_SESSION['error'] = 'Internal server error, please contact support';
    }
  }
  // A convenient way to stay on the same page, show a message, 
  // and prevent re-submission on reload is to combine the 
  // PRG (Post/Redirect/Get) pattern with a self-redirect
  if (isset($_SESSION['error']) || isset($_SESSION['success'])) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
  }
}

$stmt = $pdo->query('SELECT auto_id, make, year, mileage FROM autos ORDER BY auto_id ASC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <div class="col-sm-4 col-sm-offset-0" style="padding-left: 0">
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
    <?php elseif (isset($_SESSION['success'])): ?>
      <div class="container-fluid" style="padding-left: 0">
        <div class="col-sm-4 col-sm-offset-0" style="padding-left: 0">
          <div class="panel panel-success">
            <div class="panel-body text-success bg-success" style="border-radius: 3px">
              <?php
              echo $_SESSION['success'];
              // Flash the success message to prevent it 
              // from showing up on further page reloads
              unset($_SESSION['success']);
              ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <form method="post" class="form-inline">
      <div class="form-group" style="margin-right: 1em">
        <label for="make" class="control-label">Make:</label>
        <input type="text" name="make" id="make" class="form-control">
      </div>
      <div class="form-group" style="margin-right: 1em">
        <label for="year" class="control-label">Year:</label>
        <input type="text" name="year" id="year" class="form-control">
      </div>
      <div class="form-group" style="margin-right: 1em">
        <label for="mileage" class="control-label">Mileage:</label>
        <input type="text" name="mileage" id="mileage" class="form-control">
      </div>
      <div class="form-group">
        <input type="submit" name="add" value="Add" class="btn btn-primary">
        <input type="submit" name="logout" value="Log Out" class="btn btn-default">
      </div>
    </form>
    <br />

    <h2>Automobiles</h2>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Make</th>
          <th>Year</th>
          <th>Mileage</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td>
              <?= htmlentities($row['auto_id']) ?>
            </td>
            <td>
              <?= htmlentities($row['make']) ?>
            </td>
            <td>
              <?= htmlentities($row['year']) ?>
            </td>
            <td>
              <?= htmlentities($row['mileage']) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>
</body>

</html>