<?php

$dbname = "KingGoat";
$dbuser = "KingGoat";
$dbpass = "goat5arecool";

$pdo = new PDO("mysql:host=localhost;" .
              "dbname=$dbname;" .
              "port:3307;" .
              'charset=utf8',
              $dbuser,
              $dbpass);