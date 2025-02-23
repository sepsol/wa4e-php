<?php

session_start();

require_once 'utils/pdo.php';
require_once 'utils/redirect.php';
require_once 'utils/validations.php';

if (isset($_POST['cancel'])) {
  redirect('index.php');
}

if (isset($_POST['login'])) {
  try {
    validate_account();
    $saltedPasswordHash = hash_password($_POST['pass']);
    try {
      $account = get_account($_POST['email'], $saltedPasswordHash);
    } catch (Exception $ex) {
      error_log(basename(__FILE__) . ', SQL error=' . $ex->getMessage());
      throw new Exception('Internal server error, please contact support');
    }
    if (!$account) throw new Exception('Invalid credentials');
    error_log("Login success " . $_POST['email'] . " $saltedPasswordHash");
    $_SESSION['account'] = $account;
    redirect('index.php');
  } catch (Exception $ex) {
    error_log('Login fail ' . $_POST['email'] || '[no email]' . ' ' . $saltedPasswordHash || '[no password]');
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
    <h1>Please Log In</h1>
    <br>

    <noscript style="color: red;">This page needs JavaScript enabled to function properly</noscript>
    <?php include 'utils/flash.php' ?>

    <form method="post" class="form-horizontal">
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
          <input type="submit" name="login" value="Log In" class="btn btn-primary" onclick="return validate()">
          <input type="submit" name="cancel" value="Cancel" class="btn btn-default">
        </div>
      </div>
    </form>

    <p>
      Don&apos;t have an account? <a href="register.php">Register here</a>
    </p>

    <p>
      <em>
        For a password hint, view source and find a password hint
        in the HTML comments.
      </em>
      <!-- Hint: 
        The account is umsi@umich.edu
        The password is the three character name of the 
        programming language used in this class (all lower case) 
        followed by 123.
      -->
    </p>

  </div>

  <script type="text/javascript">
    function validate() {
      console.log('Validating...');
      try {
        email = document.getElementById('email-input').value;
        password = document.getElementById('password-input').value;
        console.log(`Validating em="${email}" pw="${password}"`);
        if (!email || !password) {
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
</body>

</html>