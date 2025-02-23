<?php

// Start a new session with cookie 
// or connect to an existing one
session_start();

if (isset($_POST['cancel'])) {
  header('Location: index.php');
  exit();
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

if (isset($_POST['login']) && isset($_POST['email']) && isset($_POST['pass'])) {
  if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
    $_SESSION['error'] = 'User name and password are required';
  } elseif (!strpos($_POST['email'], '@')) {
    $_SESSION['error'] = 'Email must have an at-sign (@)';
  } else {
    $input_hash = hash('md5', $salt . $_POST['pass']);
    if ($input_hash === $stored_hash) {
      error_log("Login success " . $_POST['email'] . " $input_hash");
      $_SESSION['name'] = $_POST['email'];
    } else {
      error_log("Login fail " . $_POST['email'] . " $input_hash");
      $_SESSION['error'] = 'Incorrect password';
    }
  }
  // A convenient way to stay on the same page, show a message, 
  // and prevent re-submission on reload is to combine the 
  // PRG (Post/Redirect/Get) pattern with a self-redirect
  if (isset($_SESSION['error'])) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
  } else if (isset($_SESSION['name'])) {
    header('Location: index.php');
    exit();
  }
}

////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html lang="en">

<?php require 'modules/head.php'; ?>

<body>
  <div class="container">
    <h1>Please Log In</h1>
    <br />

    <?php if (isset($_SESSION['error'])): ?>
      <div class="container-fluid" style="padding-left: 0">
        <div class="col-sm-offset-2 col-sm-4">
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
    <?php endif; ?>

    <form method="post" class="form-horizontal">
      <div class="form-group">
        <label for="email" class="col-sm-2 control-label">Email:</label>
        <div class="col-sm-4">
          <input type="text" name="email" id="email" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="password" class="col-sm-2 control-label">Password:</label>
        <div class="col-sm-4">
          <input type="password" name="pass" id="password" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <input type="submit" name="login" value="Log In" class="btn btn-primary">
          <input type="submit" name="cancel" value="Cancel" class="btn btn-default">
        </div>
      </div>
    </form>

    <p>
      For a password hint, view source and find a password hint
      in the HTML comments.
      <!-- Hint: The password is the three character name of the 
      programming language used in this class (all lower case) 
      followed by 123. -->
    </p>

  </div>
</body>

</html>