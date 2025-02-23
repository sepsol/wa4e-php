<!DOCTYPE html>
<html lang="en">

<?php require 'modules/head.php'; ?>

<body>
  <div class="container">
    <h1>Welcome to Autos Database</h1>
    <p>
      <a href="login.php">Please Log In</a>
    </p>
    <ul>
      <li>
        Attempt to go to
        <a href="view.php">view.php</a> without logging in - it should fail with an error message.
      </li>
      <li>
        Attempt to go to
        <a href="add.php">add.php</a> without logging in - it should fail with an error message.
      </li>
      <li>
        Attempt to go to
        <a href="autos.php">autos.php</a> without logging in - it should fail with an error message.
      </li>
    </ul>
    <p>
      <a href="https://www.wa4e.com/assn/autosess/" target="_blank">Specification for this Application</a>
    </p>
  </div>
</body>