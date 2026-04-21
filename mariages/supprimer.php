<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

if ($_SESSION['role'] === 'analyste') {
    die("Accès refusé");
}

$id = $_GET['id'] ?? null;
$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

if (!$id) {
    die("Identifiant invalide");
}

$stmtTemoins = $pdo->prepare("DELETE FROM temoins_mariage WHERE mariage_id = ?");
$stmtTemoins->execute([$id]);

if ($role === 'administrateur') {
    $stmt = $pdo->prepare("DELETE FROM mariages WHERE id = ?");
    $stmt->execute([$id]);
} else {
    $stmt = $pdo->prepare("DELETE FROM mariages WHERE id = ? AND arrondissement_id = ?");
    $stmt->execute([$id, $arrondissement_id]);
}

header('Location: liste.php');
exit;