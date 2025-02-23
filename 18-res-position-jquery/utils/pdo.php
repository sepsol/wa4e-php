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
  $stmt = $pdo->prepare('SELECT position_id, year, description FROM positions WHERE profile_id = :pid ORDER BY rank ASC');
  $stmt->execute([
    ':pid' => $profile_id,
  ]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $rows;
}

function insert_profile_positions(int $profile_id) {
  global $pdo;

  $rank = 1;
  for($i = 1; $i <= 9; $i++) {
    if (!isset($_POST["year$i"]) ) continue;
    if (!isset($_POST["desc$i"]) ) continue;

    $year = $_POST["year$i"];
    $desc = $_POST["desc$i"];

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
}

function delete_profile(int $profile_id) {
  global $pdo;
  $stmt = $pdo->prepare('DELETE FROM profiles WHERE profile_id = :pid');
  $stmt->execute([
    ':pid' => $profile_id,
  ]);
  delete_profile_positions($_POST['profile_id']);
}
