<?php

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=assignment", "appuser", "apppass");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$passwordSalt = 'XyZzy12*_';

function hash_password(string $password) {
  global $passwordSalt;
  $saltedPasswordHash = hash('md5', $passwordSalt . $password);
  return $saltedPasswordHash;
}

function get_account(string $email, string $salted_password_hash) {
  global $pdo;
  $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
  $stmt->execute([
    ':em' => $email,
    ':pw' => $salted_password_hash,
  ]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  return $row;
}

function insert_account(string $name, string $email, string $salted_password_hash) {
  global $pdo;
  $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:nm, :em, :pw)');
  $stmt->execute([
    ':nm' => $name,
    ':em' => $email,
    ':pw' => $salted_password_hash,
  ]);
  return $pdo->lastInsertId();
}

function get_profile_positions(int $profile_id) {
  global $pdo;
  $stmt = $pdo->prepare('SELECT year, description FROM positions WHERE profile_id = :pid ORDER BY rank ASC');
  $stmt->execute([
    ':pid' => $profile_id,
  ]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $rows;
}

function insert_profile_positions(int $profile_id) {
  global $pdo;

  $rank = 1;
  for ($i = 1; $i <= 9; $i++) {
    if (!isset($_POST["pos_year_$i"])) continue;
    if (!isset($_POST["pos_desc_$i"])) continue;

    $year = $_POST["pos_year_$i"];
    $desc = $_POST["pos_desc_$i"];

    $stmt = $pdo->prepare('INSERT INTO positions (profile_id, rank, year, description) VALUES (:pid, :rank, :year, :desc)');
    $stmt->execute([
      ':pid' => $profile_id,
      ':rank' => $rank,
      ':year' => $year,
      ':desc' => $desc,
    ]);

    $rank++;
  }  
}

function delete_profile_positions(int $profile_id) {
  global $pdo;
  $stmt = $pdo->prepare('DELETE FROM positions WHERE profile_id = :pid');
  $stmt->execute([
    ':pid' => $profile_id,
  ]);
}

function get_profile_educations(int $profile_id) {
  global $pdo;
  $stmt = $pdo->prepare('SELECT educations.year AS year, institutions.name AS school FROM educations JOIN institutions ON educations.institution_id = institutions.institution_id WHERE educations.profile_id = :pid ORDER BY educations.rank ASC');
  $stmt->execute([
    ':pid' => $profile_id,
  ]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $rows;
}

function insert_profile_educations(int $profile_id) {
  global $pdo;

  $rank = 1;
  for ($i = 1; $i <= 9; $i++) {
    if (!isset($_POST["edu_year_$i"])) continue;
    if (!isset($_POST["edu_school_$i"])) continue;

    $year = $_POST["edu_year_$i"];
    $school = $_POST["edu_school_$i"];

    $stmt = $pdo->prepare('SELECT institution_id FROM institutions WHERE name = :sch');
    $stmt->execute([
      ':sch' => $school,
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $institution_id = null;
    if ($row) {
      $institution_id = $row['institution_id'];
    } else {
      $stmt = $pdo->prepare('INSERT INTO institutions (name) VALUES (:sch)');
      $stmt->execute([
        ':sch' => $school,
      ]);
      $institution_id = $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare('INSERT INTO educations (profile_id, institution_id, rank, year) VALUES (:pid, :instid, :rank, :year)');
    $stmt->execute([
      ':pid' => $profile_id,
      ':instid' => $institution_id,
      ':rank' => $rank,
      ':year' => $year,
    ]);

    $rank++;
  }
}

function delete_profile_educations(int $profile_id) {
  global $pdo;
  $stmt = $pdo->prepare('DELETE FROM educations WHERE profile_id = :pid');
  $stmt->execute([
    ':pid' => $profile_id,
  ]);
}

function get_all_profiles() {
  global $pdo;
  $stmt = $pdo->query('SELECT profile_id, user_id, first_name, last_name, headline FROM profiles ORDER BY profile_id ASC');
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $rows;
}

function get_profile(int $profile_id) {
  global $pdo;
  $stmt = $pdo->prepare('SELECT profile_id, user_id, first_name, last_name, email, headline, summary FROM profiles WHERE profile_id = :pid');
  $stmt->execute([
    ':pid' => $profile_id,
  ]);
  $profile = $stmt->fetch(PDO::FETCH_ASSOC);

  $positions = get_profile_positions($profile_id);
  $profile['positions'] = $positions ?? [];
  
  $educations = get_profile_educations($profile_id);
  $profile['educations'] = $educations ?? [];
  
  return $profile;
}

function insert_profile() {
  global $pdo;
  $stmt = $pdo->prepare('INSERT INTO profiles (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :hl, :sm)');
  $stmt->execute([
    ':uid' => $_POST['user_id'],
    ':fn' => $_POST['first_name'],
    ':ln' => $_POST['last_name'],
    ':em' => $_POST['email'],
    ':hl' => $_POST['headline'],
    ':sm' => $_POST['summary'],
  ]);
  $profile_id = $pdo->lastInsertId();
  insert_profile_positions($profile_id);
  insert_profile_educations($profile_id);
  return $profile_id;
}

function edit_profile() {
  global $pdo;
  $stmt = $pdo->prepare('UPDATE profiles SET first_name=:fn, last_name=:ln, email=:em, headline=:hl, summary=:sm WHERE profile_id = :pid');
  $stmt->execute([
    ':fn' => $_POST['first_name'],
    ':ln' => $_POST['last_name'],
    ':em' => $_POST['email'],
    ':hl' => $_POST['headline'],
    ':sm' => $_POST['summary'],
    ':pid' => $_POST['profile_id'],
  ]);

  delete_profile_positions($_POST['profile_id']);
  insert_profile_positions($_POST['profile_id']);

  delete_profile_educations($_POST['profile_id']);
  insert_profile_educations($_POST['profile_id']);
}

function delete_profile(int $profile_id) {
  global $pdo;
  $stmt = $pdo->prepare('DELETE FROM profiles WHERE profile_id = :pid');
  $stmt->execute([
    ':pid' => $profile_id,
  ]);
  delete_profile_positions($_POST['profile_id']);
  delete_profile_educations($_POST['profile_id']);
}
