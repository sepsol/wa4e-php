<?php

if (!isset($_SESSION['account'])) {
  die('ACCESS DENIED');
}