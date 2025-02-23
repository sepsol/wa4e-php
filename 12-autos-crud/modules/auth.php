<?php
// Start a new session with cookie 
// or connect to an existing one
session_start();

if (!isset($_SESSION['user_id']) || strlen($_SESSION['user_id']) < 1) {
  die('ACCESS DENIED');
}