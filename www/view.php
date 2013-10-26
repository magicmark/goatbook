<?php

include '../db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM goats WHERE id = ?");
$stmt->bindValue(1, $id,    PDO::PARAM_INT);
$stmt->execute();
print_r( $stmt->fetchAll(PDO::FETCH_ASSOC));  

