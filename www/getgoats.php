<?php

include "../db.php";
if (!isset($_GET['lastgoat'])) {
  die('uh oh!');
}

$stmt = $pdo>prepare(
  "SELECT id, uploaded, file FROM goats " .
  "WHERE id > ? ORDER BY uploaded DESC LIMIT 50"
);
$stmt->bindValue(1, $_POST['lastgoat'], PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);  
print_r($results);
