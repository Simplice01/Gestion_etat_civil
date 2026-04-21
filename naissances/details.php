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
    $sql = "SELECT n.*, a.nom AS nom_arrondissement
            FROM naissances n
            LEFT JOIN arrondissements a ON n.arrondissement_id = a.id
            WHERE n.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
} else {
    $sql = "SELECT n.*, a.nom AS nom_arrondissement
            FROM naissances n
            LEFT JOIN arrondissements a ON n.arrondissement_id = a.id
            WHERE n.id = ? AND n.arrondissement_id = ?";
    $stmt = $pdo->prepare($sql);
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
    <div class="details-container">
        <h1>Détails de la naissance</h1>

        <div class="details-card">
            <div class="details-section">
                <h2>Informations générales</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Numéro d'acte</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['numero_acte']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Arrondissement</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['nom_arrondissement']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Nom(s) de l'enfant</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['noms_enfant']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Prénom(s) de l'enfant</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['prenoms_enfant']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Sexe</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['sexe_enfant']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Date de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['date_naissance_enfant']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Lieu de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['lieu_naissance_enfant']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Date de déclaration</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['date_declaration']); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h2>Informations sur la mère</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Nom</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['nom_mere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Prénom(s)</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['prenom_mere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Date de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['date_naissance_mere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Lieu de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['lieu_naissance_mere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Profession</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['profession_mere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Domicile</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['domicile_mere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Nationalité</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['nationalite_mere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">NPI mère</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['npi_mere'] ?? ''); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h2>Informations sur le père</h2>
                <div class="details-grid">
                    <div class="details-item">
                        <span class="details-label">Nom</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['nom_pere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Prénom(s)</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['prenom_pere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Date de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['date_naissance_pere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Lieu de naissance</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['lieu_naissance_pere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Profession</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['profession_pere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Domicile</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['domicile_pere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">Nationalité</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['nationalite_pere']); ?></span>
                    </div>

                    <div class="details-item">
                        <span class="details-label">NPI père</span>
                        <span class="details-value"><?php echo htmlspecialchars($naissance['npi_pere'] ?? ''); ?></span>
                    </div>
                </div>
            </div>

            <div class="details-actions">
                <a class="back-link" href="liste.php">Retour à la liste</a>
                <a class="pdf-link" href="../pdf/naissance_pdf.php?id=<?php echo $naissance['id']; ?>" target="_blank">Générer le PDF</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>