<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

if ($_SESSION['role'] === 'analyste') {
    die("Accès refusé");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: liste.php');
    exit;
}

$id = $_POST['id'] ?? null;
$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

if (!$id) {
    die("Identifiant invalide");
}

$noms_enfant = trim($_POST['noms_enfant'] ?? '');
$prenoms_enfant = trim($_POST['prenoms_enfant'] ?? '');
$sexe_enfant = trim($_POST['sexe_enfant'] ?? '');
$date_naissance_enfant = $_POST['date_naissance_enfant'] ?? null;
$lieu_naissance_enfant = trim($_POST['lieu_naissance_enfant'] ?? '');
$nom_mere = trim($_POST['nom_mere'] ?? '');
$prenom_mere = trim($_POST['prenom_mere'] ?? '');
$nom_pere = trim($_POST['nom_pere'] ?? '');
$prenom_pere = trim($_POST['prenom_pere'] ?? '');
$date_declaration = $_POST['date_declaration'] ?? null;

if ($role === 'administrateur') {
    $sql = "UPDATE naissances SET
            noms_enfant = ?,
            prenoms_enfant = ?,
            sexe_enfant = ?,
            date_naissance_enfant = ?,
            lieu_naissance_enfant = ?,
            nom_mere = ?,
            prenom_mere = ?,
            nom_pere = ?,
            prenom_pere = ?,
            date_declaration = ?,
            updated_at = NOW()
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $noms_enfant,
        $prenoms_enfant,
        $sexe_enfant,
        $date_naissance_enfant,
        $lieu_naissance_enfant,
        $nom_mere,
        $prenom_mere,
        $nom_pere,
        $prenom_pere,
        $date_declaration,
        $id
    ]);
} else {
    $sql = "UPDATE naissances SET
            noms_enfant = ?,
            prenoms_enfant = ?,
            sexe_enfant = ?,
            date_naissance_enfant = ?,
            lieu_naissance_enfant = ?,
            nom_mere = ?,
            prenom_mere = ?,
            nom_pere = ?,
            prenom_pere = ?,
            date_declaration = ?,
            updated_at = NOW()
            WHERE id = ? AND arrondissement_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $noms_enfant,
        $prenoms_enfant,
        $sexe_enfant,
        $date_naissance_enfant,
        $lieu_naissance_enfant,
        $nom_mere,
        $prenom_mere,
        $nom_pere,
        $prenom_pere,
        $date_declaration,
        $id,
        $arrondissement_id
    ]);
}

header('Location: liste.php');
exit;