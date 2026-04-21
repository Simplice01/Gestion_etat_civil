<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

if ($_SESSION['role'] === 'analyste') {
    die("Accès refusé");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ajouter.php');
    exit;
}

$numero_acte = 'DEC-' . date('YmdHis');
$arrondissement_id = $_SESSION['arrondissement_id'];
$utilisateur_id = $_SESSION['utilisateur_id'];

$noms_defunt = trim($_POST['noms_defunt'] ?? '');
$prenoms_defunt = trim($_POST['prenoms_defunt'] ?? '');
$date_naissance_defunt = $_POST['date_naissance_defunt'] ?: null;
$lieu_naissance_defunt = trim($_POST['lieu_naissance_defunt'] ?? '');
$profession_defunt = trim($_POST['profession_defunt'] ?? '');
$dernier_domicile_defunt = trim($_POST['dernier_domicile_defunt'] ?? '');

$date_deces = $_POST['date_deces'] ?? null;
$heure_deces = $_POST['heure_deces'] ?: null;
$lieu_deces = trim($_POST['lieu_deces'] ?? '');

$nom_declarant = trim($_POST['nom_declarant'] ?? '');
$prenoms_declarant = trim($_POST['prenoms_declarant'] ?? '');
$lien_parente_declarant = trim($_POST['lien_parente_declarant'] ?? '');
$documents_justificatifs = trim($_POST['documents_justificatifs'] ?? '');

$sql = "INSERT INTO deces (
    arrondissement_id,
    utilisateur_id,
    numero_acte,
    noms_defunt,
    prenoms_defunt,
    date_naissance_defunt,
    lieu_naissance_defunt,
    profession_defunt,
    dernier_domicile_defunt,
    date_deces,
    heure_deces,
    lieu_deces,
    nom_declarant,
    prenoms_declarant,
    lien_parente_declarant,
    documents_justificatifs,
    created_at,
    updated_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $arrondissement_id,
    $utilisateur_id,
    $numero_acte,
    $noms_defunt,
    $prenoms_defunt,
    $date_naissance_defunt,
    $lieu_naissance_defunt,
    $profession_defunt,
    $dernier_domicile_defunt,
    $date_deces,
    $heure_deces,
    $lieu_deces,
    $nom_declarant,
    $prenoms_declarant,
    $lien_parente_declarant,
    $documents_justificatifs
]);

header('Location: liste.php');
exit;