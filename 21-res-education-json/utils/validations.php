<?php

function validate_account() {
  if (
    strlen($_POST['email']) < 1
    || strlen($_POST['pass']) < 1
    || (isset($_POST['name']) && strlen($_POST['name']) < 1)
  ) {
    throw new Exception('All fields are required');
  } 

  if (!strpos($_POST['email'], '@')) {
    throw new Exception('Email must have an at-sign (@)');
  }

  return true;
}

function validate_profile() {
  if (
    strlen($_POST['first_name']) < 1
    || strlen($_POST['last_name']) < 1
    || strlen($_POST['email']) < 1
    || strlen($_POST['headline']) < 1
    || strlen($_POST['summary']) < 1
  ) {
    throw new Exception('All fields are required');
  }

  for ($i = 1; $i <= 9; $i++) {
    if (!isset($_POST["pos_year_$i"])) continue;
    if (!isset($_POST["pos_desc_$i"])) continue;
    $year = $_POST["pos_year_$i"];
    $desc = $_POST["pos_desc_$i"];

    if (strlen($year) < 1 || strlen($desc) < 1) {
      throw new Exception('All fields are required');
    }
  }

  for ($i = 1; $i <= 9; $i++) {
    if (!isset($_POST["edu_year_$i"])) continue;
    if (!isset($_POST["edu_school_$i"])) continue;
    $year = $_POST["edu_year_$i"];
    $school = $_POST["edu_school_$i"];

    if (strlen($year) < 1 || strlen($school) < 1) {
      throw new Exception('All fields are required');
    }
  }

  if (!strpos($_POST['email'], '@')) {
    throw new Exception('Email must have an at-sign (@)');
  }

  for ($i = 1; $i <= 9; $i++) {
    if (!isset($_POST["pos_year_$i"])) continue;
    $year = $_POST["pos_year_$i"];
    if (!is_numeric($year)) {
      throw new Exception('Position year must be numeric');
    }
  }

  for ($i = 1; $i <= 9; $i++) {
    if (!isset($_POST["edu_year_$i"])) continue;
    $year = $_POST["edu_year_$i"];
    if (!is_numeric($year)) {
      throw new Exception('Education year must be numeric');
    }
  }

  return true;
}
