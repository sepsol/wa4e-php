<?php



$pdo = new PDO("mysql:host=localhost;port=3306;dbname=assignment", "appuser", "apppass");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
  $result = $stmt->execute([
    ':nm' => $name,
    ':em' => $email,
    ':pw' => $salted_password_hash,
  ]);
  return $result;
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
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  return $row;
}

/**
 * @param array{user_id: int, first_name: string, last_name: string, email: string, headline: string, summary: string} $profile_data
 * @return bool
 */
function insert_profile(array $profile_data) {
  global $pdo;
  $stmt = $pdo->prepare('INSERT INTO profiles (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :hl, :sm)');
  $result = $stmt->execute([
    ':uid' => $profile_data['user_id'],
    ':fn' => $profile_data['first_name'],
    ':ln' => $profile_data['last_name'],
    ':em' => $profile_data['email'],
    ':hl' => $profile_data['headline'],
    ':sm' => $profile_data['summary'],
  ]);
  return $result;
}

/**
 * @param array{profile_id: int, first_name: string, last_name: string, email: string, headline: string, summary: string} $profile_data
 * @return bool
 */
function edit_profile(array $profile_data) {
  global $pdo;
  $stmt = $pdo->prepare('UPDATE profiles SET first_name=:fn, last_name=:ln, email=:em, headline=:hl, summary=:sm WHERE profile_id = :pid');
  $result = $stmt->execute([
    ':fn' => $profile_data['first_name'],
    ':ln' => $profile_data['last_name'],
    ':em' => $profile_data['email'],
    ':hl' => $profile_data['headline'],
    ':sm' => $profile_data['summary'],
    ':pid' => $profile_data['profile_id'],
  ]);
  return $result;
}

function delete_profile(int $profile_id) {
  global $pdo;
  $stmt = $pdo->prepare('DELETE FROM profiles WHERE profile_id = :pid');
  $result = $stmt->execute([
    ':pid' => $profile_id,
  ]);
  return $result;
}
