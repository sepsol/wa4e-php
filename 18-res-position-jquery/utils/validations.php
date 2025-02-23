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
    if (!isset($_POST["year$i"]) ) continue;
    if (!isset($_POST["desc$i"]) ) continue;
    $year = $_POST["year$i"];
    $desc = $_POST["desc$i"];

    if (strlen($year) < 1 || strlen($desc) < 1) {
      throw new Exception('All fields are required');
    }
  }

  if (!strpos($_POST['email'], '@')) {
    throw new Exception('Email must have an at-sign (@)');
  }

  for ($i = 1; $i <= 9; $i++) {
    if (!isset($_POST["year$i"]) ) continue;
    if (!isset($_POST["desc$i"]) ) continue;
    $year = $_POST["year$i"];
    $desc = $_POST["desc$i"];

    if (!is_numeric($year)) {
      throw new Exception('Position year must  be numeric');
    }
  }

  return true;
}
