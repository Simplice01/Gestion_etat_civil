<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

if ($_SESSION['role'] === 'analyste') {
    die("Accès refusé");
}

$id = $_GET['id'] ?? null;
$arrondissement_id = $_SESSION['arrondissement_id'];
$role = $_SESSION['role'];

if (!$id) {
    die("Identifiant invalide");
}

if ($role === 'administrateur') {
    $stmt = $pdo->prepare("SELECT * FROM naissances WHERE id = ?");
    $stmt->execute([$id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM naissances WHERE id = ? AND arrondissement_id = ?");
    $stmt->execute([$id, $arrondissement_id]);
}

$naissance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$naissance) {
    die("Enregistrement introuvable ou accès refusé");
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Modifier une naissance</h1>

    <form action="modifier_traitement.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $naissance['id']; ?>">

        <div>
            <label>Noms de l'enfant</label>
            <input type="text" name="noms_enfant" value="<?php echo htmlspecialchars($naissance['noms_enfant']); ?>" required>
        </div>

        <div>
            <label>Prénoms de l'enfant</label>
            <input type="text" name="prenoms_enfant" value="<?php echo htmlspecialchars($naissance['prenoms_enfant']); ?>" required>
        </div>

        <div>
            <label>Sexe</label>
            <select name="sexe_enfant" required>
                <option value="M" <?php echo $naissance['sexe_enfant'] === 'M' ? 'selected' : ''; ?>>Masculin</option>
                <option value="F" <?php echo $naissance['sexe_enfant'] === 'F' ? 'selected' : ''; ?>>Féminin</option>
            </select>
        </div>

        <div>
            <label>Date de naissance</label>
            <input type="date" name="date_naissance_enfant" value="<?php echo htmlspecialchars($naissance['date_naissance_enfant']); ?>" required>
        </div>

        <div>
            <label>Lieu de naissance</label>
            <input type="text" name="lieu_naissance_enfant" value="<?php echo htmlspecialchars($naissance['lieu_naissance_enfant']); ?>" required>
        </div>

        <div>
            <label>Nom mère</label>
            <input type="text" name="nom_mere" value="<?php echo htmlspecialchars($naissance['nom_mere']); ?>" required>
        </div>

        <div>
            <label>Prénom mère</label>
            <input type="text" name="prenom_mere" value="<?php echo htmlspecialchars($naissance['prenom_mere']); ?>" required>
        </div>

        <div>
            <label>Nom père</label>
            <input type="text" name="nom_pere" value="<?php echo htmlspecialchars($naissance['nom_pere']); ?>">
        </div>

        <div>
            <label>Prénom père</label>
            <input type="text" name="prenom_pere" value="<?php echo htmlspecialchars($naissance['prenom_pere']); ?>">
        </div>

        <div>
            <label>Date de déclaration</label>
            <input type="date" name="date_declaration" value="<?php echo htmlspecialchars($naissance['date_declaration']); ?>" required>
        </div>

        <br>
        <button type="submit">Mettre à jour</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>