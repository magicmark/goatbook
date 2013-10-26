<?php

include '../db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM goats WHERE id = ?");
$stmt->bindValue(1, $id,    PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo '<img src="goatfaces/'.$result[0]['file'].'">';
