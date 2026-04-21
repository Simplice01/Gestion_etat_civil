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
    $stmt = $pdo->prepare("SELECT * FROM deces WHERE id = ?");
    $stmt->execute([$id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM deces WHERE id = ? AND arrondissement_id = ?");
    $stmt->execute([$id, $arrondissement_id]);
}

$deces = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$deces) {
    die("Enregistrement introuvable ou accès refusé");
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Modifier un décès</h1>

    <form action="modifier_traitement.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $deces['id']; ?>">

        <div>
            <label>Noms du défunt</label>
            <input type="text" name="noms_defunt" value="<?php echo htmlspecialchars($deces['noms_defunt']); ?>" required>
        </div>

        <div>
            <label>Prénoms du défunt</label>
            <input type="text" name="prenoms_defunt" value="<?php echo htmlspecialchars($deces['prenoms_defunt']); ?>" required>
        </div>

        <div>
            <label>Date de décès</label>
            <input type="date" name="date_deces" value="<?php echo htmlspecialchars($deces['date_deces']); ?>" required>
        </div>

        <div>
            <label>Heure de décès</label>
            <input type="time" name="heure_deces" value="<?php echo htmlspecialchars($deces['heure_deces']); ?>">
        </div>

        <div>
            <label>Lieu de décès</label>
            <input type="text" name="lieu_deces" value="<?php echo htmlspecialchars($deces['lieu_deces']); ?>" required>
        </div>

        <div>
            <label>Nom du déclarant</label>
            <input type="text" name="nom_declarant" value="<?php echo htmlspecialchars($deces['nom_declarant']); ?>" required>
        </div>

        <div>
            <label>Prénoms du déclarant</label>
            <input type="text" name="prenoms_declarant" value="<?php echo htmlspecialchars($deces['prenoms_declarant']); ?>" required>
        </div>

        <div>
            <label>Lien de parenté du déclarant</label>
            <input type="text" name="lien_parente_declarant" value="<?php echo htmlspecialchars($deces['lien_parente_declarant']); ?>">
        </div>

        <div>
            <label>Documents justificatifs</label>
            <textarea name="documents_justificatifs" rows="4"><?php echo htmlspecialchars($deces['documents_justificatifs']); ?></textarea>
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>