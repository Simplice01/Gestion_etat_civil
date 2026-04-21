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

$numero_acte = 'NAIS-' . date('YmdHis');
$arrondissement_id = $_SESSION['arrondissement_id'];
$utilisateur_id = $_SESSION['utilisateur_id'];

$noms_enfant = trim($_POST['noms_enfant'] ?? '');
$prenoms_enfant = trim($_POST['prenoms_enfant'] ?? '');
$sexe_enfant = trim($_POST['sexe_enfant'] ?? '');
$date_naissance_enfant = $_POST['date_naissance_enfant'] ?? null;
$lieu_naissance_enfant = trim($_POST['lieu_naissance_enfant'] ?? '');

$nom_mere = trim($_POST['nom_mere'] ?? '');
$prenom_mere = trim($_POST['prenom_mere'] ?? '');
$date_naissance_mere = $_POST['date_naissance_mere'] ?: null;
$lieu_naissance_mere = trim($_POST['lieu_naissance_mere'] ?? '');
$profession_mere = trim($_POST['profession_mere'] ?? '');
$domicile_mere = trim($_POST['domicile_mere'] ?? '');
$nationalite_mere = trim($_POST['nationalite_mere'] ?? '');

$nom_pere = trim($_POST['nom_pere'] ?? '');
$prenom_pere = trim($_POST['prenom_pere'] ?? '');
$date_naissance_pere = $_POST['date_naissance_pere'] ?: null;
$lieu_naissance_pere = trim($_POST['lieu_naissance_pere'] ?? '');
$profession_pere = trim($_POST['profession_pere'] ?? '');
$domicile_pere = trim($_POST['domicile_pere'] ?? '');
$nationalite_pere = trim($_POST['nationalite_pere'] ?? '');
$npi_parent = trim($_POST['npi_parent'] ?? '');

$date_declaration = $_POST['date_declaration'] ?? null;

$sql = "INSERT INTO naissances (
    arrondissement_id,
    utilisateur_id,
    numero_acte,
    noms_enfant,
    prenoms_enfant,
    sexe_enfant,
    date_naissance_enfant,
    lieu_naissance_enfant,
    nom_mere,
    prenom_mere,
    date_naissance_mere,
    lieu_naissance_mere,
    profession_mere,
    domicile_mere,
    nationalite_mere,
    nom_pere,
    prenom_pere,
    date_naissance_pere,
    lieu_naissance_pere,
    profession_pere,
    domicile_pere,
    nationalite_pere,
    npi_parent,
    date_declaration,
    created_at,
    updated_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  NOW(), NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $arrondissement_id,
    $utilisateur_id,
    $numero_acte,
    $noms_enfant,
    $prenoms_enfant,
    $sexe_enfant,
    $date_naissance_enfant,
    $lieu_naissance_enfant,
    $nom_mere,
    $prenom_mere,
    $date_naissance_mere,
    $lieu_naissance_mere,
    $profession_mere,
    $domicile_mere,
    $nationalite_mere,
    $nom_pere,
    $prenom_pere,
    $date_naissance_pere,
    $lieu_naissance_pere,
    $profession_pere,
    $domicile_pere,
    $nationalite_pere,
    $npi_parent,
    $date_declaration
]);

header('Location: liste.php');
exit;