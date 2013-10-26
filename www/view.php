<?php

include '../db.php';

$stmt = $pdo->prepare("SELECT * FROM goats");
$stmt->execute();
print_r( $stmt->fetchAll(PDO::FETCH_ASSOC));  

