<?php

session_start();

require 'utils/auth.php';
require_once 'utils/pdo.php';

header('Content-Type: application/json; charset=utf-8');

try {
  $stmt = $pdo->prepare('SELECT name FROM institutions WHERE name LIKE :prefix');
  $stmt->execute([
    ':prefix' => ($_REQUEST['term'] ?? '') . '%',
  ]);
} catch (Exception $ex) {
  error_log(basename(__FILE__) . ', SQL error=' . $ex->getMessage());
  echo 'Internal server error, please contact support';
  exit();
}

$uni_names = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $uni_names[] = $row['name'];
}

echo json_encode($uni_names, JSON_PRETTY_PRINT);
