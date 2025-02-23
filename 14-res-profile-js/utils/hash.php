<?php

$passwordSalt = 'XyZzy12*_';
function hash_password(string $password) {
  global $passwordSalt;
  $saltedPasswordHash = hash('md5', $passwordSalt . $password);
  return $saltedPasswordHash;
}