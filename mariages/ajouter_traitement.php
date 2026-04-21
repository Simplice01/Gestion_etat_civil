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

$numero_acte = 'MAR-' . date('YmdHis');
$arrondissement_id = $_SESSION['arrondissement_id'];
$utilisateur_id = $_SESSION['utilisateur_id'];

$nom_epoux = trim($_POST['nom_epoux'] ?? '');
$prenoms_epoux = trim($_POST['prenoms_epoux'] ?? '');
$date_naissance_epoux = $_POST['date_naissance_epoux'] ?: null;
$lieu_naissance_epoux = trim($_POST['lieu_naissance_epoux'] ?? '');
$npi_epoux = trim($_POST['npi_epoux'] ?? '');
$profession_epoux = trim($_POST['profession_epoux'] ?? '');
$domicile_epoux = trim($_POST['domicile_epoux'] ?? '');

$nom_epouse = trim($_POST['nom_epouse'] ?? '');
$prenoms_epouse = trim($_POST['prenoms_epouse'] ?? '');
$date_naissance_epouse = $_POST['date_naissance_epouse'] ?: null;
$lieu_naissance_epouse = trim($_POST['lieu_naissance_epouse'] ?? '');
$npi_epouse = trim($_POST['npi_epouse'] ?? '');
$profession_epouse = trim($_POST['profession_epouse'] ?? '');
$domicile_epouse = trim($_POST['domicile_epouse'] ?? '');

$nom_parent_epoux = trim($_POST['nom_parent_epoux'] ?? '');
$prenom_parent_epoux = trim($_POST['prenom_parent_epoux'] ?? '');
$profession_parent_epoux = trim($_POST['profession_parent_epoux'] ?? '');
$domicile_parent_epoux = trim($_POST['domicile_parent_epoux'] ?? '');

$nom_parent_epouse = trim($_POST['nom_parent_epouse'] ?? '');
$prenom_parent_epouse = trim($_POST['prenom_parent_epouse'] ?? '');
$profession_parent_epouse = trim($_POST['profession_parent_epouse'] ?? '');
$domicile_parent_epouse = trim($_POST['domicile_parent_epouse'] ?? '');

$noms_temoin = trim($_POST['noms_temoin'] ?? '');
$prenoms_temoin = trim($_POST['prenoms_temoin'] ?? '');
$profession_temoin = trim($_POST['profession_temoin'] ?? '');
$domicile_temoin = trim($_POST['domicile_temoin'] ?? '');

$date_celebration = $_POST['date_celebration'] ?? null;
$lieu_celebration = trim($_POST['lieu_celebration'] ?? '');
$regime_matrimonial = trim($_POST['regime_matrimonial'] ?? '');

$sql = "INSERT INTO mariages (
    arrondissement_id,
    utilisateur_id,
    numero_acte,
    nom_epoux,
    prenoms_epoux,
    date_naissance_epoux,
    lieu_naissance_epoux,
    npi_epoux,
    profession_epoux,
    domicile_epoux,
    nom_epouse,
    prenoms_epouse,
    date_naissance_epouse,
    lieu_naissance_epouse,
    npi_epouse,
    profession_epouse,
    domicile_epouse,
    nom_parent_epoux,
    prenom_parent_epoux,
    profession_parent_epoux,
    domicile_parent_epoux,
    nom_parent_epouse,
    prenom_parent_epouse,
    profession_parent_epouse,
    domicile_parent_epouse,
    date_celebration,
    lieu_celebration,
    regime_matrimonial,
    created_at,
    updated_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $arrondissement_id,
    $utilisateur_id,
    $numero_acte,
    $nom_epoux,
    $prenoms_epoux,
    $date_naissance_epoux,
    $lieu_naissance_epoux,
    $npi_epoux,
    $profession_epoux,
    $domicile_epoux,
    $nom_epouse,
    $prenoms_epouse,
    $date_naissance_epouse,
    $lieu_naissance_epouse,
    $npi_epouse,
    $profession_epouse,
    $domicile_epouse,
    $nom_parent_epoux,
    $prenom_parent_epoux,
    $profession_parent_epoux,
    $domicile_parent_epoux,
    $nom_parent_epouse,
    $prenom_parent_epouse,
    $profession_parent_epouse,
    $domicile_parent_epouse,
    $date_celebration,
    $lieu_celebration,
    $regime_matrimonial
]);

$mariage_id = $pdo->lastInsertId();

if (!empty($noms_temoin) || !empty($prenoms_temoin)) {
    $sqlTemoin = "INSERT INTO temoins_mariage (
        mariage_id,
        noms_temoin,
        prenoms_temoin,
        profession_temoin,
        domicile_temoin
    ) VALUES (?, ?, ?, ?, ?)";
    $stmtTemoin = $pdo->prepare($sqlTemoin);
    $stmtTemoin->execute([
        $mariage_id,
        $noms_temoin,
        $prenoms_temoin,
        $profession_temoin,
        $domicile_temoin
    ]);
}

header('Location: liste.php');
exit;