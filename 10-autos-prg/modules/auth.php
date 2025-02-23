<?php
// Start a new session with cookie 
// or connect to an existing one
session_start();

if (!isset($_SESSION['name']) || strlen($_SESSION['name']) < 1) {
  die('Not logged in');
}