<?php

require_once __DIR__ . '/../lib/Util.php';

if ($argc != 4) {
  die("Usage: {$argv[0]} <username> <email> <password>\n");
}

try {
  $u = Model::factory('User')->create();

  $u->username = $argv[1];
  $u->email = $argv[2];
  $u->password = password_hash($argv[3], PASSWORD_DEFAULT);

  $u->save();

  print "User {$u->username} (ID = {$u->id}) created!.\n";

} catch (Exception $e) {
  print "ERROR: {$e->getMessage()}\n";
  print "User not created due to error!\n";
}

