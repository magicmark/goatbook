<?php

include "../db.php";
if (!isset($_GET['lastgoat'])) {
  die('uh oh!');
}

$stmt = $pdo->prepare(
  "SELECT id, uploaded, file FROM goats " .
  "WHERE id > (SELECT MAX(id) - ? FROM goats) ORDER BY uploaded DESC"
);
$stmt->bindValue(1, $_GET['lastgoat'], PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);  
$json = json_encode($results);
echo $json;