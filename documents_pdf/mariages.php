<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

if ($role === 'administrateur') {
    $sql = "SELECT d.*, m.numero_acte, a.nom AS nom_arrondissement
            FROM documents_generes d
            INNER JOIN mariages m ON d.reference_id = m.id
            LEFT JOIN arrondissements a ON m.arrondissement_id = a.id
            WHERE d.type_document = 'mariage'
            ORDER BY d.date_generation DESC";
    $stmt = $pdo->query($sql);
} else {
    $sql = "SELECT d.*, m.numero_acte, a.nom AS nom_arrondissement
            FROM documents_generes d
            INNER JOIN mariages m ON d.reference_id = m.id
            LEFT JOIN arrondissements a ON m.arrondissement_id = a.id
            WHERE d.type_document = 'mariage'
              AND m.arrondissement_id = ?
            ORDER BY d.date_generation DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$arrondissement_id]);
}

$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>PDF des actes de mariage</h1>

    <table>
        <thead>
            <tr>
                <th>Numéro d'acte</th>
                <th>Arrondissement</th>
                <th>Date de génération</th>
                <th>PDF</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($documents) > 0): ?>
                <?php foreach ($documents as $document): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($document['numero_acte']); ?></td>
                        <td><?php echo htmlspecialchars($document['nom_arrondissement']); ?></td>
                        <td><?php echo htmlspecialchars($document['date_generation']); ?></td>
                        <td>
                            <a class="btn-action btn-pdf" href="../<?php echo htmlspecialchars($document['chemin_fichier']); ?>" target="_blank">
                                Ouvrir PDF
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucun document PDF trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>