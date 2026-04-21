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

if ($role === 'administrateur') {
    $stmt = $pdo->prepare("SELECT * FROM mariages WHERE id = ?");
    $stmt->execute([$id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM mariages WHERE id = ? AND arrondissement_id = ?");
    $stmt->execute([$id, $arrondissement_id]);
}

$mariage = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mariage) {
    die("Enregistrement introuvable ou accès refusé");
}

$stmtTemoin = $pdo->prepare("SELECT * FROM temoins_mariage WHERE mariage_id = ? LIMIT 1");
$stmtTemoin->execute([$id]);
$temoin = $stmtTemoin->fetch(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Modifier un mariage</h1>

    <form action="modifier_traitement.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $mariage['id']; ?>">

        <div>
            <label>Nom époux</label>
            <input type="text" name="nom_epoux" value="<?php echo htmlspecialchars($mariage['nom_epoux']); ?>" required>
        </div>

        <div>
            <label>Prénoms époux</label>
            <input type="text" name="prenoms_epoux" value="<?php echo htmlspecialchars($mariage['prenoms_epoux']); ?>" required>
        </div>

        <div>
            <label>Nom épouse</label>
            <input type="text" name="nom_epouse" value="<?php echo htmlspecialchars($mariage['nom_epouse']); ?>" required>
        </div>

        <div>
            <label>Prénoms épouse</label>
            <input type="text" name="prenoms_epouse" value="<?php echo htmlspecialchars($mariage['prenoms_epouse']); ?>" required>
        </div>

        <div>
            <label>Date célébration</label>
            <input type="date" name="date_celebration" value="<?php echo htmlspecialchars($mariage['date_celebration']); ?>" required>
        </div>

        <div>
            <label>Lieu célébration</label>
            <input type="text" name="lieu_celebration" value="<?php echo htmlspecialchars($mariage['lieu_celebration']); ?>" required>
        </div>

        <div>
            <label>Régime matrimonial</label>
            <select name="regime_matrimonial">
                <option value="communauté" <?php echo $mariage['regime_matrimonial'] === 'communauté' ? 'selected' : ''; ?>>Communauté</option>
                <option value="séparation" <?php echo $mariage['regime_matrimonial'] === 'séparation' ? 'selected' : ''; ?>>Séparation</option>
            </select>
        </div>

        <div>
            <label>Nom témoin</label>
            <input type="text" name="noms_temoin" value="<?php echo htmlspecialchars($temoin['noms_temoin'] ?? ''); ?>">
        </div>

        <div>
            <label>Prénoms témoin</label>
            <input type="text" name="prenoms_temoin" value="<?php echo htmlspecialchars($temoin['prenoms_temoin'] ?? ''); ?>">
        </div>

        <div>
            <label>Profession témoin</label>
            <input type="text" name="profession_temoin" value="<?php echo htmlspecialchars($temoin['profession_temoin'] ?? ''); ?>">
        </div>

        <div>
            <label>Domicile témoin</label>
            <input type="text" name="domicile_temoin" value="<?php echo htmlspecialchars($temoin['domicile_temoin'] ?? ''); ?>">
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>