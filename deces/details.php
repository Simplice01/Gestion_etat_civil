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
    $sql = "SELECT d.*, a.nom AS nom_arrondissement
            FROM deces d
            LEFT JOIN arrondissements a ON d.arrondissement_id = a.id
            WHERE d.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
} else {
    $sql = "SELECT d.*, a.nom AS nom_arrondissement
            FROM deces d
            LEFT JOIN arrondissements a ON d.arrondissement_id = a.id
            WHERE d.id = ? AND d.arrondissement_id = ?";
    $stmt = $pdo->prepare($sql);
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
    <div class="details-container">
        <h1>Détails du décès</h1>

        <div class="details-card">

            <div class="details-section">
                <h2>Informations générales</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Numéro d'acte</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['numero_acte']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Arrondissement</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['nom_arrondissement']); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h2>Informations sur le défunt</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Nom(s)</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['noms_defunt']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Prénom(s)</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['prenoms_defunt']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Date de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['date_naissance_defunt']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Lieu de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['lieu_naissance_defunt']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Profession</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['profession_defunt']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Dernier domicile</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['dernier_domicile_defunt']); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h2>Informations sur le décès</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Date de décès</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['date_deces']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Heure de décès</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['heure_deces']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Lieu de décès</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['lieu_deces']); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h2>Informations sur le déclarant</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Nom complet</span>
                        <span class="details-value">
                            <?php echo htmlspecialchars($deces['nom_declarant'] . ' ' . $deces['prenoms_declarant']); ?>
                        </span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Lien de parenté</span>
                        <span class="details-value"><?php echo htmlspecialchars($deces['lien_parente_declarant']); ?></span>
                    </div>

                    <div class="details-item" style="grid-column: 1 / -1;">
                        <span class="details-label">Documents justificatifs</span>
                        <span class="details-value"><?php echo nl2br(htmlspecialchars($deces['documents_justificatifs'])); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-actions">
                <a class="back-link" href="liste.php">Retour à la liste</a>
                <a class="pdf-link" href="../pdf/deces_pdf.php?id=<?php echo $deces['id']; ?>" target="_blank">Générer le PDF</a>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>