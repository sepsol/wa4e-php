<?php

if (isset($_POST['cancel'])) {
  header('Location: index.php');
  exit();
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

$error = '';

if (isset($_POST['login']) && isset($_POST['who']) && isset($_POST['pass'])) {
  if (strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1) {
    $error = 'Email and password are required';
  } elseif (!strpos($_POST['who'], '@')) {
    $error = 'Email must have an at-sign (@)';
  } else {
    $input_hash = hash('md5', $salt . $_POST['pass']);
    if ($input_hash === $stored_hash) {
      error_log("Login success " . $_POST['who'] . " $input_hash");
      header('Location: autos.php?name=' . urlencode($_POST['who']));
      exit();
    } else {
      error_log("Login fail " . $_POST['who'] . " $input_hash");
      $error = 'Incorrect password';
    }
  }
}

?>

<!----------------------------------------------------------------------------->

<!DOCTYPE html>
<html lang="en">

<?php require 'modules/head.php'; ?>

<body>
  <div class="container">
    <h1>Please Log In</h1>
    <br />

    <?php if ($error): ?>
    <div class="container-fluid" style="padding-left: 0">
      <div class="col-sm-4 col-sm-offset-0" style="padding-left: 0">
        <div class="panel panel-danger">
          <div class="panel-body text-danger bg-danger" style="border-radius: 3px">
            <?= $error ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <form method="post" class="form-horizontal">
      <div class="form-group">
        <label for="email" class="col-sm-1 control-label">Email:</label>
        <div class="col-sm-3">
          <input type="text" name="who" id="email" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="password" class="col-sm-1 control-label">Password:</label>
        <div class="col-sm-3">
          <input type="password" name="pass" id="password" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-1 col-sm-3">
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