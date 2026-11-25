<?php
require_once 'config/database.php';
session_start();

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM resumes WHERE id = ?");
$stmt->execute([$id]);

header("Location: dashboard.php");
exit;