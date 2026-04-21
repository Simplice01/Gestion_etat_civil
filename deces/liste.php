<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

if ($role === 'administrateur') {
    $sql = "SELECT d.*, a.nom AS nom_arrondissement
            FROM deces d
            LEFT JOIN arrondissements a ON d.arrondissement_id = a.id
            ORDER BY d.id DESC";
    $stmt = $pdo->query($sql);
} else {
    $sql = "SELECT d.*, a.nom AS nom_arrondissement
            FROM deces d
            LEFT JOIN arrondissements a ON d.arrondissement_id = a.id
            WHERE d.arrondissement_id = ?
            ORDER BY d.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$arrondissement_id]);
}

$deces = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Liste des décès</h1>

    <?php if ($_SESSION['role'] !== 'analyste'): ?>
        <p><a href="ajouter.php" class="btn-link">Ajouter un décès</a></p>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <input 
            type="text" 
            id="searchDeces" 
            class="search-input"
            placeholder="Rechercher par numéro d'acte, nom du défunt, lieu, déclarant..."
        >
    </div>

    <div id="resultats-deces">
        <table>
            <thead>
                <tr>
                    <th>N° Acte</th>
                    <th>Nom du défunt</th>
                    <th>Date décès</th>
                    <th>Lieu décès</th>
                    <th>Déclarant</th>
                    <th>Arrondissement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($deces) > 0): ?>
                    <?php foreach ($deces as $deces_item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($deces_item['numero_acte']); ?></td>
                            <td><?php echo htmlspecialchars($deces_item['noms_defunt'] . ' ' . $deces_item['prenoms_defunt']); ?></td>
                            <td><?php echo htmlspecialchars($deces_item['date_deces']); ?></td>
                            <td><?php echo htmlspecialchars($deces_item['lieu_deces']); ?></td>
                            <td><?php echo htmlspecialchars($deces_item['nom_declarant'] . ' ' . $deces_item['prenoms_declarant']); ?></td>
                            <td><?php echo htmlspecialchars($deces_item['nom_arrondissement']); ?></td>
                            <td class="actions-cell">
                                <a class="btn-action btn-view" href="details.php?id=<?php echo $deces_item['id']; ?>">
                                    Voir
                                </a>

                                <?php if ($_SESSION['role'] !== 'analyste'): ?>
                                    <a class="btn-action btn-edit" href="modifier.php?id=<?php echo $deces_item['id']; ?>">
                                        Modifier
                                    </a>

                                    <a class="btn-action btn-delete" 
                                      href="supprimer.php?id=<?php echo $deces_item['id']; ?>" 
                                      onclick="return confirm('Voulez-vous vraiment supprimer cet enregistrement ?');">
                                        Supprimer
                                    </a>
                                <?php endif; ?>
                            </td>
                                                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Aucun décès enregistré.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchDeces').addEventListener('keyup', function () {
    let motcle = this.value;

    fetch('recherche_ajax.php?search=' + encodeURIComponent(motcle))
        .then(response => response.text())
        .then(data => {
            document.getElementById('resultats-deces').innerHTML = data;
        });
});
</script>

<?php include '../includes/footer.php'; ?>