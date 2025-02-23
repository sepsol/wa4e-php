<?php

session_start();

require 'utils/auth.php';
require_once 'utils/pdo.php';
require_once 'utils/redirect.php';

$profile = null;

if (!isset($_GET['profile_id'])) {
  $_SESSION['error'] = 'Bad URL parameters';
  redirect('index.php');
}

try {
  try {
    $profile = get_profile($_GET['profile_id']);
  } catch (Exception $ex) {
    error_log(basename(__FILE__) . ', SQL error=' . $ex->getMessage());
    throw new Exception('Internal server error, please contact support');
  }
  if (!$profile)
    throw new Exception('Profile not found');
} catch (Exception $ex) {
  $_SESSION['error'] = $ex->getMessage() ?? 'Something went wrong, please contact support';
  redirect('index.php');
}

if ($profile['user_id'] !== $_SESSION['account']['user_id']) {
  die('NOT AUTHORIZED');
}

if (isset($_POST['cancel'])) {
  redirect('index.php');
}

if (isset($_POST['delete'])) {
  try {
    try {
      delete_profile($_POST['profile_id']);
    } catch (Exception $ex) {
      error_log(basename(__FILE__) . ', SQL error=' . $ex->getMessage());
      throw new Exception('Internal server error, please contact support');
    }
    $_SESSION['success'] = 'Profile deleted';
    redirect('index.php');
  } catch (Exception $ex) {
    $_SESSION['error'] = $ex->getMessage() ?? 'Something went wrong, please contact support';
    redirect($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
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
    <h1>Delete Resume Profile</h1>
    <br>

    <noscript style="color: red;">This page needs JavaScript enabled to function properly</noscript>
    <?php include 'utils/flash.php' ?>

    <form method="post" class="form-horizontal">
      <input type="hidden" name="profile_id" id="profileid-input" value="<?= $profile['profile_id'] ?>" readonly>
      <div class="form-group">
        <label for="firstname-input" class="col-sm-2 control-label">First Name:</label>
        <div class="col-sm-3">
          <input type="text" name="first_name" id="firstname-input" class="form-control"
            value="<?= htmlentities($profile['first_name']) ?>" readonly>
        </div>
      </div>
      <div class="form-group">
        <label for="lastname-input" class="col-sm-2 control-label">Last Name:</label>
        <div class="col-sm-3">
          <input type="text" name="last_name" id="lastname-input" class="form-control"
            value="<?= htmlentities($profile['last_name']) ?>" readonly>
        </div>
      </div>
      <div class="form-group">
        <label for="email-input" class="col-sm-2 control-label">Email:</label>
        <div class="col-sm-4">
          <input type="text" name="email" id="email-input" class="form-control"
            value="<?= htmlentities($profile['email']) ?>" readonly>
        </div>
      </div>
      <br>
      <div class="form-group">
        <label for="headline-input" class="col-sm-2 control-label">Headline:</label>
        <div class="col-sm-6">
          <input type="text" name="headline" id="headline-input" class="form-control"
            value="<?= htmlentities($profile['headline']) ?>" readonly>
        </div>
      </div>
      <div class="form-group">
        <label for="summary-input" class="col-sm-2 control-label">Summary:</label>
        <div class="col-sm-6">
          <textarea name="summary" id="summary-input" class="form-control" rows="8" cols="80"
            readonly><?= htmlentities($profile['summary']) ?></textarea>
        </div>
      </div>
      <div id="positions-container">
        <?php foreach ($profile['positions'] as $index => $position): ?>
          <div id="position-<?= $position['position_id'] ?>">
            <br>
            <div class="form-group">
              <label for="year-<?= $index + 1 ?>-input" class="col-sm-2 control-label">Year:</label>
              <div class="col-sm-2">
                <input type="text" name="year<?= $index + 1 ?>" id="year-<?= $index + 1 ?>-input" class="form-control"
                  value="<?= htmlentities($position['year']) ?>" readonly>
              </div>
            </div>
            <div class="form-group" id="position-<?= $index + 1 ?>">
              <label for="desc-<?= $index + 1 ?>-input" class="col-sm-2 control-label">Description:</label>
              <div class="col-sm-6">
                <textarea name="desc<?= $index + 1 ?>" id="desc-<?= $index + 1 ?>-input" class="form-control" rows="8"
                  cols="80" readonly><?= htmlentities($position['description']) ?></textarea>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <br>
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