<?php

session_start();

require 'utils/auth.php';
require_once 'utils/pdo.php';
require_once 'utils/redirect.php';
require_once 'utils/validations.php';

if (isset($_POST['cancel'])) {
  redirect('index.php');
}

if (isset($_POST['add'])) {
  try {
    validate_profile();
    try {
      insert_profile();
    } catch (Exception $ex) {
      error_log(basename(__FILE__) . ', SQL error=' . $ex->getMessage());
      throw new Exception('Internal server error, please contact support');
    }
    $_SESSION['success'] = 'Profile added';
    redirect('index.php');
  } catch (Exception $ex) {
    $_SESSION['error'] = $ex->getMessage() ?? 'Something went wrong, please contact support';
    redirect($_SERVER['PHP_SELF']);
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
    <br>

    <noscript style="color: red;">This page needs JavaScript enabled to function properly</noscript>
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
      <br>
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
      <div id="positions-container"></div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6">
          <button type="button" id="add-position-button" class="btn btn-link">
            <span>
              <span class="glyphicon glyphicon-plus" style="position: static; bottom: 0; font-size: 0.8em;" aria-hidden="true"></span>
              Add Position
            </span>
          </button>
        </div>
      </div>
      <br>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-3">
          <input type="submit" name="add" value="Add" class="btn btn-primary">
          <input type="submit" name="cancel" value="Cancel" class="btn btn-default">
        </div>
      </div>
    </form>
  </div>

  <script type="text/javascript" src="utils/positionsHelper.js"></script>
</body>

</html>