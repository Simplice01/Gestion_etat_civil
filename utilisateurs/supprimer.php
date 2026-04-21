<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

if ($_SESSION['role'] !== 'administrateur') {
    die("Accès refusé");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Identifiant invalide");
}

if ($id == $_SESSION['utilisateur_id']) {
    die("Vous ne pouvez pas supprimer votre propre compte.");
}

$stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);

header('Location: liste.php');
exit;