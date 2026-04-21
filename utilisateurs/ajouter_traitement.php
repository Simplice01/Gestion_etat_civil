<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

if ($_SESSION['role'] !== 'administrateur') {
    die("Accès refusé");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ajouter.php');
    exit;
}

$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$mot_de_passe = $_POST['mot_de_passe'] ?? '';
$role_id = $_POST['role_id'] ?? null;
$arrondissement_id = $_POST['arrondissement_id'] !== '' ? $_POST['arrondissement_id'] : null;
$statut = trim($_POST['statut'] ?? 'actif');

if (
    empty($nom) || empty($prenom) || empty($username) ||
    empty($email) || empty($mot_de_passe) || empty($role_id)
) {
    die("Veuillez remplir tous les champs obligatoires.");
}

$mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

$sql = "INSERT INTO utilisateurs (
    arrondissement_id,
    role_id,
    nom,
    prenom,
    email,
    username,
    mot_de_passe,
    statut,
    created_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $arrondissement_id,
    $role_id,
    $nom,
    $prenom,
    $email,
    $username,
    $mot_de_passe_hash,
    $statut
]);

header('Location: liste.php');
exit;