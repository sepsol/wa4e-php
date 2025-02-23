<?php

session_start();

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
  if (!$profile) throw new Exception('Profile not found');
} catch (Exception $ex) {
  $_SESSION['error'] = $ex->getMessage() ?? 'Something went wrong, please contact support';
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
    <br>

    <noscript style="color: red;">This page needs JavaScript enabled to function properly</noscript>
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
      <br>
      <div class="form-group">
        <label class="col-sm-2" style="text-align: right">Headline:</label>
        <div class="col-sm-6"><?= htmlentities($profile['headline']) ?></div>
      </div>
      <div class="form-group">
        <label class="col-sm-2" style="text-align: right">Summary:</label>
        <div class="col-sm-6"><?= htmlentities($profile['summary']) ?></div>
      </div>
      <div id="educations-container">
        <?php foreach ($profile['educations'] as $index => $education): ?>
          <div id="education-<?= $index + 1 ?>">
            <br>
            <div class="form-group">
              <label class="col-sm-2" style="text-align: right">Year:</label>
              <div class="col-sm-6"><?= htmlentities($education['year']) ?></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2" style="text-align: right">School:</label>
              <div class="col-sm-6"><?= htmlentities($education['school']) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div id="positions-container">
        <?php foreach ($profile['positions'] as $index => $position): ?>
          <div id="position-<?= $index + 1 ?>">
            <br>
            <div class="form-group">
              <label class="col-sm-2" style="text-align: right">Year:</label>
              <div class="col-sm-6"><?= htmlentities($position['year']) ?></div>
            </div>
            <div class="form-group">
              <label class="col-sm-2" style="text-align: right">Description:</label>
              <div class="col-sm-6"><?= htmlentities($position['description']) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <br>
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