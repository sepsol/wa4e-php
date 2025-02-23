<?php

session_start();

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
} catch (Exception $ex) {
  error_log("view.php, SQL error=" . $ex->getMessage());
  $_SESSION['error'] = 'Internal server error, please contact support';
  redirect('index.php');
}

if (isset($_POST['done'])) {
  redirect('index.php');
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
    <h1>View Resume Profile</h1>
    <br />

    <?php include 'utils/flash.php' ?>

    <div class="form-horizontal">
      <div class="form-group">
        <label class="col-sm-2" style="text-align: right">First Name:</label>
        <div class="col-sm-6"><?= htmlentities($profile['first_name']) ?></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2" style="text-align: right">Last Name:</label>
        <div class="col-sm-6"><?= htmlentities($profile['last_name']) ?></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2" style="text-align: right">Email:</label>
        <div class="col-sm-6"><?= htmlentities($profile['email']) ?></div>
      </div>
      <br />
      <div class="form-group">
        <label class="col-sm-2" style="text-align: right">Headline:</label>
        <div class="col-sm-6"><?= htmlentities($profile['headline']) ?></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2" style="text-align: right">Summary:</label>
        <div class="col-sm-6"><?= htmlentities($profile['summary']) ?></div>
      </div>
    </div>

    <br />
    <form method="post" class="form-horizontal">
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-3">
          <input type="submit" name="done" value="Done" class="btn btn-primary">
        </div>
      </div>
    </form>
  </div>
</body>

</html>