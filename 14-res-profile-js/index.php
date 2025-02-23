<?php

session_start();
require_once 'utils/pdo.php';
$profiles = get_all_profiles();

################################################################################

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'utils/head.php' ?>
</head>

<body>
  <div class="container">
    <h1>Resume Registry</h1>
    <br />

    <?php include 'utils/flash.php' ?>

    <?php if (!isset($_SESSION['account'])): ?>
      <p>
        <a href="login.php">Please log in</a>
      </p>
    <?php else: ?>
      <p>
        <a href="add.php">Add New Entry</a>
        |
        <a href="logout.php">Logout</a>
      </p>
    <?php endif; ?>

    <?php if (count($profiles) > 0): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Headline</th>
            <?php if (isset($_SESSION['account'])): ?>
              <th>Action</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($profiles as $profile): ?>
            <tr id="profile-<?= $profile['profile_id'] ?>">
              <td>
                <a href="view.php?profile_id=<?= $profile['profile_id'] ?>" class="btn btn-link">
                  <?= htmlentities($profile['first_name']) ?>
                  <?= htmlentities($profile['last_name']) ?>
                </a>
              </td>
              <td style="vertical-align: middle">
                <div>
                  <?= htmlentities($profile['headline']) ?>
                </div>
              </td>
              <?php if (isset($_SESSION['account'])): ?>
                <td>
                  <a
                    <?php if($profile['user_id'] === $_SESSION['account']['user_id']): ?>
                      href="edit.php?profile_id=<?= $profile['profile_id'] ?>"
                      class="btn btn-link"
                    <?php else: ?>
                      href="#"
                      class="btn btn-link disabled"
                    <?php endif; ?>
                  >Edit</a>
                  /
                  <a
                    <?php if($profile['user_id'] === $_SESSION['account']['user_id']): ?>
                      href="delete.php?profile_id=<?= $profile['profile_id'] ?>"
                      class="btn btn-link"
                    <?php else: ?>
                      href="#"
                      class="btn btn-link disabled"
                    <?php endif; ?>
                  >Delete</a>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>
        No records found
      </p>
    <?php endif; ?>

    <br />
    <p>
      <a href="https://www.wa4e.com/assn/res-profile/" target="_blank">
        Specification for this Application
      </a>
    </p>
  </div>
</body>