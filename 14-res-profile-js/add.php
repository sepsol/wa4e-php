<?php

session_start();

require 'utils/auth.php';
require_once 'utils/pdo.php';
require_once('utils/redirect.php');

if (isset($_POST['cancel'])) {
  redirect('index.php');
}

if (isset($_POST['add'])) {
  if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
    $_SESSION['error'] = 'All fields are required';
  } elseif (!strpos($_POST['email'], '@')) {
    $_SESSION['error'] = 'Email must have an at-sign (@)';
  } else {
    try {
      insert_profile([
        'user_id' => $_POST['user_id'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'headline' => $_POST['headline'],
        'summary' => $_POST['summary'],
      ]);
      $_SESSION['success'] = 'Profile added';
    } catch (Exception $ex) {
      error_log("autos.php, SQL error=" . $ex->getMessage());
      $_SESSION['error'] = 'Internal server error, please contact support';
    }
  }

  if (isset($_SESSION['error'])) {
    redirect($_SERVER['PHP_SELF']);
  } else if ($_SESSION['success']) {
    redirect('index.php');
  }
}

################################################################################

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'utils/head.php' ?>
</head>

<body>
  <div class="container">
    <h1>Add Resume Profile</h1>
    <br />

    <?php include 'utils/flash.php' ?>

    <form method="post" class="form-horizontal">
      <input type="hidden" name="user_id" id="userid-input" value="<?= $_SESSION['account']['user_id'] ?>" readonly>
      <div class="form-group">
        <label for="firstname-input" class="col-sm-2 control-label">First Name:</label>
        <div class="col-sm-3">
          <input type="text" name="first_name" id="firstname-input" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="lastname-input" class="col-sm-2 control-label">Last Name:</label>
        <div class="col-sm-3">
          <input type="text" name="last_name" id="lastname-input" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="email-input" class="col-sm-2 control-label">Email:</label>
        <div class="col-sm-4">
          <input type="text" name="email" id="email-input" class="form-control">
        </div>
      </div>
      <br />
      <div class="form-group">
        <label for="headline-input" class="col-sm-2 control-label">Headline:</label>
        <div class="col-sm-6">
          <input type="text" name="headline" id="headline-input" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="summary-input" class="col-sm-2 control-label">Summary:</label>
        <div class="col-sm-6">
          <textarea name="summary" id="summary-input" class="form-control" rows="8" cols="80"></textarea>
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