<?php

require 'vendor/autoload.php';
require_once('models/Database.php');
require_once('models/UserDatabase.php');


$dbContext = new Database();

$dbContext->getUsersDatabase()->getAuth()->logOut();
header('Location: /');
?>