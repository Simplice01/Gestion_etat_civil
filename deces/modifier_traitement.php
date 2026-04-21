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

$noms_defunt = trim($_POST['noms_defunt'] ?? '');
$prenoms_defunt = trim($_POST['prenoms_defunt'] ?? '');
$date_deces = $_POST['date_deces'] ?? null;
$heure_deces = $_POST['heure_deces'] ?: null;
$lieu_deces = trim($_POST['lieu_deces'] ?? '');
$nom_declarant = trim($_POST['nom_declarant'] ?? '');
$prenoms_declarant = trim($_POST['prenoms_declarant'] ?? '');
$lien_parente_declarant = trim($_POST['lien_parente_declarant'] ?? '');
$documents_justificatifs = trim($_POST['documents_justificatifs'] ?? '');

if ($role === 'administrateur') {
    $sql = "UPDATE deces SET
            noms_defunt = ?,
            prenoms_defunt = ?,
            date_deces = ?,
            heure_deces = ?,
            lieu_deces = ?,
            nom_declarant = ?,
            prenoms_declarant = ?,
            lien_parente_declarant = ?,
            documents_justificatifs = ?,
            updated_at = NOW()
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $noms_defunt,
        $prenoms_defunt,
        $date_deces,
        $heure_deces,
        $lieu_deces,
        $nom_declarant,
        $prenoms_declarant,
        $lien_parente_declarant,
        $documents_justificatifs,
        $id
    ]);
} else {
    $sql = "UPDATE deces SET
            noms_defunt = ?,
            prenoms_defunt = ?,
            date_deces = ?,
            heure_deces = ?,
            lieu_deces = ?,
            nom_declarant = ?,
            prenoms_declarant = ?,
            lien_parente_declarant = ?,
            documents_justificatifs = ?,
            updated_at = NOW()
            WHERE id = ? AND arrondissement_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $noms_defunt,
        $prenoms_defunt,
        $date_deces,
        $heure_deces,
        $lieu_deces,
        $nom_declarant,
        $prenoms_declarant,
        $lien_parente_declarant,
        $documents_justificatifs,
        $id,
        $arrondissement_id
    ]);
}

header('Location: liste.php');
exit;