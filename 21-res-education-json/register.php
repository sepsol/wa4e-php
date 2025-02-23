<?php

session_start();

require_once 'utils/pdo.php';
require_once 'utils/redirect.php';
require_once 'utils/validations.php';

if (isset($_POST['cancel'])) {
  redirect('index.php');
}

if (isset($_POST['register'])) {
  try {
    validate_account();
    $saltedPasswordHash = hash_password($_POST['pass']);
    try {
      insert_account($_POST['name'], $_POST['email'], $saltedPasswordHash);
    } catch (Exception $ex) {
      error_log(basename(__FILE__) . ', SQL error=' . $ex->getMessage());
      throw new Exception('Internal server error, please contact support');
    }
    error_log("Registration success " . $_POST['name'] . " " . $_POST['email'] . " $saltedPasswordHash");
    $_SESSION['success'] = 'Account created successfully, please log in';
    redirect('login.php');
  } catch (Exception $ex) {
    error_log('Registration fail ' . $_POST['name'] || '[no name]' . ' ' . $_POST['email'] || '[no email]' . ' ' . $saltedPasswordHash || '[no password]');
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
    <h1>Sign Up</h1>
    <br>

    <noscript style="color: red;">This page needs JavaScript enabled to function properly</noscript>
    <?php include 'utils/flash.php' ?>

    <form method="post" class="form-horizontal">
      <div class="form-group">
        <label for="name-input" class="col-sm-2 control-label">Name:</label>
        <div class="col-sm-4">
          <input type="text" name="name" id="name-input" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="email-input" class="col-sm-2 control-label">Email:</label>
        <div class="col-sm-4">
          <input type="text" name="email" id="email-input" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="password-input" class="col-sm-2 control-label">Password:</label>
        <div class="col-sm-4">
          <input type="password" name="pass" id="password-input" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <input type="submit" name="register" value="Register" class="btn btn-primary" onclick="return validate()">
          <input type="submit" name="cancel" value="Cancel" class="btn btn-default">
        </div>
      </div>
    </form>

    <p>
      Already have an account? <a href="login.php">Login here</a>
    </p>

    <script type="text/javascript">
      function validate() {
        console.log('Validating...');
        try {
          name = document.getElementById('name-input').value;
          email = document.getElementById('email-input').value;
          password = document.getElementById('password-input').value;
          console.log(`Validating nm="${name}" em="${email}" pw="${password}"`);
          if (!name || !email || !password) {
            alert('All fields must be filled out');
            return false;
          }
          return true;
        } catch (err) {
          console.error(err);
          return false;
        }
        return false;
      }
    </script>
  </div>
</body>

</html>