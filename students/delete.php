<?php
require_once '../config/db.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php?msg=Student+deleted+successfully");
exit;