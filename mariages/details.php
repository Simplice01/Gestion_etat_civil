<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$id = $_GET['id'] ?? null;
$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

if (!$id) {
    die("Identifiant invalide");
}

if ($role === 'administrateur') {
    $sql = "SELECT m.*, a.nom AS nom_arrondissement
            FROM mariages m
            LEFT JOIN arrondissements a ON m.arrondissement_id = a.id
            WHERE m.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
} else {
    $sql = "SELECT m.*, a.nom AS nom_arrondissement
            FROM mariages m
            LEFT JOIN arrondissements a ON m.arrondissement_id = a.id
            WHERE m.id = ? AND m.arrondissement_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $arrondissement_id]);
}

$mariage = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mariage) {
    die("Enregistrement introuvable ou accès refusé");
}

$stmtTemoins = $pdo->prepare("SELECT * FROM temoins_mariage WHERE mariage_id = ?");
$stmtTemoins->execute([$id]);
$temoins = $stmtTemoins->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <div class="details-container">
        <h1>Détails du mariage</h1>

        <div class="details-card">

            <div class="details-section">
                <h2>Informations générales</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Numéro d'acte</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['numero_acte']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Arrondissement</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['nom_arrondissement']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Date de célébration</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['date_celebration']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Lieu de célébration</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['lieu_celebration']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Régime matrimonial</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['regime_matrimonial']); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h2>Informations sur l'époux</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Nom</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['nom_epoux']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Prénom(s)</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['prenoms_epoux']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Date de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['date_naissance_epoux']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Lieu de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['lieu_naissance_epoux']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">NPI</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['npi_epoux']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Profession</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['profession_epoux']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Domicile</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['domicile_epoux']); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h2>Informations sur l'épouse</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Nom</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['nom_epouse']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Prénom(s)</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['prenoms_epouse']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Date de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['date_naissance_epouse']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Lieu de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['lieu_naissance_epouse']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">NPI</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['npi_epouse']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Profession</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['profession_epouse']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Domicile</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['domicile_epouse']); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h2>Parents de l'époux</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Nom</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['nom_parent_epoux']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Prénom(s)</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['prenom_parent_epoux']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Profession</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['profession_parent_epoux']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Domicile</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['domicile_parent_epoux']); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h2>Parents de l'épouse</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Nom</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['nom_parent_epouse']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Prénom(s)</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['prenom_parent_epouse']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Profession</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['profession_parent_epouse']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Domicile</span>
                        <span class="details-value"><?php echo htmlspecialchars($mariage['domicile_parent_epouse']); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h2>Témoin(s)</h2>

                <?php if (count($temoins) > 0): ?>
                    <div class="details-grid">
                        <?php foreach ($temoins as $temoin): ?>
                            <div class="details-item">
                                <span class="details-label">Nom complet</span>
                                <span class="details-value">
                                    <?php echo htmlspecialchars($temoin['noms_temoin'] . ' ' . $temoin['prenoms_temoin']); ?>
                                </span>

                                <br>

                                <span class="details-label">Profession</span>
                                <span class="details-value">
                                    <?php echo htmlspecialchars($temoin['profession_temoin']); ?>
                                </span>

                                <br>

                                <span class="details-label">Domicile</span>
                                <span class="details-value">
                                    <?php echo htmlspecialchars($temoin['domicile_temoin']); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Aucun témoin enregistré.</p>
                <?php endif; ?>
            </div>

            <div class="details-actions">
                <a class="back-link" href="liste.php">Retour à la liste</a>
                <a class="pdf-link" href="../pdf/mariage_pdf.php?id=<?php echo $mariage['id']; ?>" target="_blank">Générer le PDF</a>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>