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

$nom_epoux = trim($_POST['nom_epoux'] ?? '');
$prenoms_epoux = trim($_POST['prenoms_epoux'] ?? '');
$nom_epouse = trim($_POST['nom_epouse'] ?? '');
$prenoms_epouse = trim($_POST['prenoms_epouse'] ?? '');
$date_celebration = $_POST['date_celebration'] ?? null;
$lieu_celebration = trim($_POST['lieu_celebration'] ?? '');
$regime_matrimonial = trim($_POST['regime_matrimonial'] ?? '');

$noms_temoin = trim($_POST['noms_temoin'] ?? '');
$prenoms_temoin = trim($_POST['prenoms_temoin'] ?? '');
$profession_temoin = trim($_POST['profession_temoin'] ?? '');
$domicile_temoin = trim($_POST['domicile_temoin'] ?? '');

if ($role === 'administrateur') {
    $sql = "UPDATE mariages SET
            nom_epoux = ?,
            prenoms_epoux = ?,
            nom_epouse = ?,
            prenoms_epouse = ?,
            date_celebration = ?,
            lieu_celebration = ?,
            regime_matrimonial = ?,
            updated_at = NOW()
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nom_epoux,
        $prenoms_epoux,
        $nom_epouse,
        $prenoms_epouse,
        $date_celebration,
        $lieu_celebration,
        $regime_matrimonial,
        $id
    ]);
} else {
    $sql = "UPDATE mariages SET
            nom_epoux = ?,
            prenoms_epoux = ?,
            nom_epouse = ?,
            prenoms_epouse = ?,
            date_celebration = ?,
            lieu_celebration = ?,
            regime_matrimonial = ?,
            updated_at = NOW()
            WHERE id = ? AND arrondissement_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nom_epoux,
        $prenoms_epoux,
        $nom_epouse,
        $prenoms_epouse,
        $date_celebration,
        $lieu_celebration,
        $regime_matrimonial,
        $id,
        $arrondissement_id
    ]);
}

$stmtCheckTemoin = $pdo->prepare("SELECT id FROM temoins_mariage WHERE mariage_id = ? LIMIT 1");
$stmtCheckTemoin->execute([$id]);
$temoinExistant = $stmtCheckTemoin->fetch(PDO::FETCH_ASSOC);

if ($temoinExistant) {
    $stmtUpdateTemoin = $pdo->prepare("UPDATE temoins_mariage SET
        noms_temoin = ?,
        prenoms_temoin = ?,
        profession_temoin = ?,
        domicile_temoin = ?
        WHERE mariage_id = ?");
    $stmtUpdateTemoin->execute([
        $noms_temoin,
        $prenoms_temoin,
        $profession_temoin,
        $domicile_temoin,
        $id
    ]);
} else {
    if (!empty($noms_temoin) || !empty($prenoms_temoin)) {
        $stmtInsertTemoin = $pdo->prepare("INSERT INTO temoins_mariage (
            mariage_id,
            noms_temoin,
            prenoms_temoin,
            profession_temoin,
            domicile_temoin
        ) VALUES (?, ?, ?, ?, ?)");
        $stmtInsertTemoin->execute([
            $id,
            $noms_temoin,
            $prenoms_temoin,
            $profession_temoin,
            $domicile_temoin
        ]);
    }
}

header('Location: liste.php');
exit;