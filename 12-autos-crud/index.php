<?php

require_once 'modules/pdo.php';

// Start a new session with cookie 
// or connect to an existing one
session_start();

if (isset($_POST['logout'])) {
  header('Location: logout.php');
  exit();
}

$stmt = $pdo->query('SELECT auto_id, make, model, year, mileage FROM autos ORDER BY auto_id ASC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html lang="en">

<?php require 'modules/head.php'; ?>

<body>
  <div class="container">
    <h2>Welcome to the Automobiles Database</h2>
    <br />

    <?php if (!isset($_SESSION['name'])): ?>
      <p>
        <a href="login.php">Please log in</a>
      </p>
      <ul>
        <li>
          Attempt to go to
          <a href="add.php">add.php</a> without logging in - it should fail with an error message.
        </li>
        <li>
          Attempt to go to
          <a href="edit.php">edit.php</a> without logging in - it should fail with an error message.
        </li>
        <li>
          Attempt to go to
          <a href="delete.php">delete.php</a> without logging in - it should fail with an error message.
        </li>
      </ul>
      <p>
        <a href="https://www.wa4e.com/assn/autoscrud/" target="_blank">Specification for this Application</a>
      </p>
    <?php else: ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="container-fluid" style="padding-left: 0">
          <div class="col-sm-offset-0 col-sm-4" style="padding-left: 0">
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
          <div class="col-sm-offset-0 col-sm-4" style="padding-left: 0">
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

      <?php if (count($rows) > 0): ?>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Make</th>
              <th>Model</th>
              <th>Year</th>
              <th>Mileage</th>
              <th>Action</th>
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
                  <?= htmlentities($row['model']) ?>
                </td>
                <td>
                  <?= htmlentities($row['year']) ?>
                </td>
                <td>
                  <?= htmlentities($row['mileage']) ?>
                </td>
                <td>
                  <a href="edit.php?auto_id=<?= $row['auto_id'] ?>">Edit</a>
                  /
                  <a href="delete.php?auto_id=<?= $row['auto_id'] ?>">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div>
          No rows found
        </div>
      <?php endif; ?>

      <br />
      <a href="add.php">Add New Entry</a>
      <br />
      <a href="logout.php">Logout</a>
    <?php endif; ?>
  </div>
</body>

</html>