<?php
echo "<pre>\n";

// Creates a PDO (PHP Data Object) connection to a MySQL server at localhost:3306
// and the database named misc, as the user 'fred' with the password 'zap'.
$pdo = new PDO(
    'mysql:host=localhost;port=3306;dbname=misc',
    'fred',
    'zap'
);

// Executes an SQL query to select all records from the users table and 
// stores the result in $stmt (a PDOStatement object).
$stmt = $pdo->query("SELECT * FROM users");
// Fetches all rows from the query result as 
// an associative array (column names as array keys).
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

print_r($rows);

echo "</pre>\n";
