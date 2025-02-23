<?php

require_once 'modules/pdo.php';

// Start a new session with cookie 
// or connect to an existing one
session_start();

if (!isset($_SESSION['name']) || strlen($_SESSION['name']) < 1) {
  die('Not logged in');
}

if (isset($_POST['logout'])) {
  header('Location: logout.php');
  exit();
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

    <?php if (isset($_SESSION['success'])): ?>
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

    <a href="add.php">Add New</a>
    |
    <a href="logout.php">Logout</a>

  </div>
</body>

</html>