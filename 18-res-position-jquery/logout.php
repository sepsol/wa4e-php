<?php

session_start();

require_once 'utils/redirect.php';

unset($_SESSION['account']);
redirect('index.php');
