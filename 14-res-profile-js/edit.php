<?php

session_start();

require 'utils/auth.php';
require_once 'utils/pdo.php';
require_once('utils/redirect.php');

if (!isset($_GET['profile_id'])) {
  $_SESSION['error'] = 'Bad URL parameters';
  redirect('index.php');
}

$profile = null;

try {
  $profile = get_profile($_GET['profile_id']);
  if (!$profile) {
    $_SESSION['error'] = 'Profile not found';
    redirect('index.php');
  }
  if ($profile['user_id'] !== $_SESSION['account']['user_id']) {
    die('NOT AUTHORIZED');
  }
} catch (Exception $ex) {
  error_log("edit.php, SQL error=" . $ex->getMessage());
  $_SESSION['error'] = 'Internal server error, please contact support';
  redirect('index.php');
}

if (isset($_POST['cancel'])) {
  redirect('index.php');
}

if (isset($_POST['edit'])) {
  if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
    $_SESSION['error'] = 'All fields are required';
  } elseif (!strpos($_POST['email'], '@')) {
    $_SESSION['error'] = 'Email must have an at-sign (@)';
  } else {
    try {
      edit_profile([
        'profile_id' => $_POST['profile_id'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'headline' => $_POST['headline'],
        'summary' => $_POST['summary'],
      ]);
      $_SESSION['success'] = 'Profile updated';
    } catch (Exception $ex) {
      error_log("edit.php, SQL error=" . $ex->getMessage());
      $_SESSION['error'] = 'Internal server error, please contact support';
    }
  }

  if (isset($_SESSION['error'])) {
    redirect($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
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
    <h1>Edit Resume Profile</h1>
    <br />

    <?php include 'utils/flash.php' ?>

    <form method="post" class="form-horizontal">
      <input type="hidden" name="profile_id" id="profileid-input" value="<?= $profile['profile_id'] ?>" readonly>
      <div class="form-group">
        <label for="firstname-input" class="col-sm-2 control-label">First Name:</label>
        <div class="col-sm-3">
          <input type="text" name="first_name" id="firstname-input" class="form-control"
            value="<?= htmlentities($profile['first_name']) ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="lastname-input" class="col-sm-2 control-label">Last Name:</label>
        <div class="col-sm-3">
          <input type="text" name="last_name" id="lastname-input" class="form-control"
            value="<?= htmlentities($profile['last_name']) ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="email-input" class="col-sm-2 control-label">Email:</label>
        <div class="col-sm-4">
          <input type="text" name="email" id="email-input" class="form-control"
            value="<?= htmlentities($profile['email']) ?>">
        </div>
      </div>
      <br />
      <div class="form-group">
        <label for="headline-input" class="col-sm-2 control-label">Headline:</label>
        <div class="col-sm-6">
          <input type="text" name="headline" id="headline-input" class="form-control"
            value="<?= htmlentities($profile['headline']) ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="summary-input" class="col-sm-2 control-label">Summary:</label>
        <div class="col-sm-6">
          <textarea name="summary" id="summary-input" class="form-control" rows="8"
            cols="80"><?= htmlentities($profile['summary']) ?></textarea>
        </div>
      </div>
      <br />
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-3">
          <input type="submit" name="edit" value="Save" class="btn btn-primary">
          <input type="submit" name="cancel" value="Cancel" class="btn btn-default">
        </div>
      </div>
    </form>
  </div>
</body>

</html>