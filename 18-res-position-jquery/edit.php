<?php

session_start();

require 'utils/auth.php';
require_once 'utils/pdo.php';
require_once 'utils/redirect.php';
require_once 'utils/validations.php';

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

if (isset($_POST['edit'])) {
  try {
    validate_profile();
    try {
      edit_profile();
    } catch (Exception $ex) {
      error_log(basename(__FILE__) . ', SQL error=' . $ex->getMessage());
      throw new Exception('Internal server error, please contact support');
    }
    $_SESSION['success'] = 'Profile updated';
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
    <h1>Edit Resume Profile</h1>
    <br>

    <noscript style="color: red;">This page needs JavaScript enabled to function properly</noscript>
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
      <br>
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
      <div id="positions-container">
        <?php foreach ($profile['positions'] as $index => $position): ?>
          <div id="position-<?= $index + 1 ?>">
            <br>
            <div class="form-group">
              <label for="year-<?= $index + 1 ?>-input" class="col-sm-2 control-label">Year:</label>
              <div class="col-sm-2">
                <input type="text" name="year<?= $index + 1 ?>" id="year-<?= $index + 1 ?>-input" class="form-control"
                  value="<?= htmlentities($position['year']) ?>">
              </div>
              <button type="button" class="btn btn-link"
                onclick="$('#position-<?= $index + 1 ?>').remove(); return false;">
                <span class="text-danger">
                  <span class="glyphicon glyphicon-remove" style="position: static; bottom: 0; font-size: 0.8em;"
                    aria-hidden="true"></span>
                  Remove Position
                </span>
              </button>
            </div>
            <div class="form-group" id="position-<?= $index + 1 ?>">
              <label for="desc-<?= $index + 1 ?>-input" class="col-sm-2 control-label">Description:</label>
              <div class="col-sm-6">
                <textarea name="desc<?= $index + 1 ?>" id="desc-<?= $index + 1 ?>-input" class="form-control" rows="8"
                  cols="80"><?= htmlentities($position['description']) ?></textarea>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6">
          <button type="button" id="add-position-button" class="btn btn-link">
            <span>
              <span class="glyphicon glyphicon-plus" style="position: static; bottom: 0; font-size: 0.8em;"
                aria-hidden="true"></span>
              Add Position
            </span>
          </button>
        </div>
      </div>
      <br>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-3">
          <input type="submit" name="edit" value="Save" class="btn btn-primary">
          <input type="submit" name="cancel" value="Cancel" class="btn btn-default">
        </div>
      </div>
    </form>
  </div>

  <script type="text/javascript" src="utils/positionsHelper.js"></script>
</body>

</html>