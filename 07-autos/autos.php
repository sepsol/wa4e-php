<?php

require_once 'modules/pdo.php';

if (!isset($_GET['name']) || strlen($_GET['name']) < 1) {
  die('Name parameter missing');
}

if (isset($_POST['logout'])) {
  header('Location: index.php');
  exit();
}

$error = '';
$success = isset($_GET['success']) ? urldecode($_GET['success']) : '';

if (isset($_POST['add']) && isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
  if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
    $error = 'Mileage and year must be numeric';
  } else if (strlen($_POST['make']) < 1) {
    $error = 'Make is required';
  } else {
    try {
      $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)');
      $stmt->execute([
        ':mk' => $_POST['make'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage'],
      ]);
      // A convenient way to stay on the same page, show a success message, and prevent re-submission on reload 
      // is to combine the PRG (Post/Redirect/Get) pattern with a self-redirect
      header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '&success=' . urlencode('Record inserted'));
      exit();
    } catch (Exception $ex) {
      error_log("autos.php, SQL error=" . $ex->getMessage());
      $error = 'Internal server error, please contact support';
      exit();
    }
  }
}

$stmt = $pdo->query('SELECT auto_id, make, year, mileage FROM autos ORDER BY auto_id ASC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!----------------------------------------------------------------------------->

<!DOCTYPE html>
<html lang="en">

<?php require 'modules/head.php'; ?>

<body>
  <div class="container">
    <h1>
      Tracking Autos for
      <code class="text-muted">
        <?= htmlentities($_REQUEST['name']) ?>
      </code>
    </h1>
    <br />

    <?php if ($error): ?>
    <div class="container-fluid" style="padding-left: 0">
      <div class="col-sm-4 col-sm-offset-0" style="padding-left: 0">
        <div class="panel panel-danger">
          <div class="panel-body text-danger bg-danger" style="border-radius: 3px">
            <?= $error ?>
          </div>
        </div>
      </div>
    </div>
    <?php elseif ($success): ?>
    <div class="container-fluid" style="padding-left: 0">
      <div class="col-sm-4 col-sm-offset-0" style="padding-left: 0">
        <div class="panel panel-success">
          <div class="panel-body text-success bg-success" style="border-radius: 3px">
            <?= $success ?>
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