<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

if ($role === 'administrateur') {
    $sql = "SELECT m.*, a.nom AS nom_arrondissement
            FROM mariages m
            LEFT JOIN arrondissements a ON m.arrondissement_id = a.id
            ORDER BY m.id DESC";
    $stmt = $pdo->query($sql);
} else {
    $sql = "SELECT m.*, a.nom AS nom_arrondissement
            FROM mariages m
            LEFT JOIN arrondissements a ON m.arrondissement_id = a.id
            WHERE m.arrondissement_id = ?
            ORDER BY m.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$arrondissement_id]);
}

$mariages = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Liste des mariages</h1>

    <?php if ($_SESSION['role'] !== 'analyste'): ?>
        <p><a href="ajouter.php" class="btn-link">Ajouter un mariage</a></p>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <input 
            type="text" 
            id="searchMariage" 
            class="search-input"
            placeholder="Rechercher par numéro d'acte, nom époux, nom épouse, lieu..."
        >
    </div>

    <div id="resultats-mariages">
        <table>
            <thead>
                <tr>
                    <th>N° Acte</th>
                    <th>Époux</th>
                    <th>Épouse</th>
                    <th>Date célébration</th>
                    <th>Lieu célébration</th>
                    <th>Arrondissement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($mariages) > 0): ?>
                    <?php foreach ($mariages as $mariage): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($mariage['numero_acte']); ?></td>
                            <td><?php echo htmlspecialchars($mariage['nom_epoux'] . ' ' . $mariage['prenoms_epoux']); ?></td>
                            <td><?php echo htmlspecialchars($mariage['nom_epouse'] . ' ' . $mariage['prenoms_epouse']); ?></td>
                            <td><?php echo htmlspecialchars($mariage['date_celebration']); ?></td>
                            <td><?php echo htmlspecialchars($mariage['lieu_celebration']); ?></td>
                            <td><?php echo htmlspecialchars($mariage['nom_arrondissement']); ?></td>
                            <td class="actions-cell">
                                <a class="btn-action btn-view" href="details.php?id=<?php echo $mariage['id']; ?>">
                                    Voir
                                </a>

                                <?php if ($_SESSION['role'] !== 'analyste'): ?>
                                    <a class="btn-action btn-edit" href="modifier.php?id=<?php echo $mariage['id']; ?>">
                                        Modifier
                                    </a>

                                    <a class="btn-action btn-delete" 
                                      href="supprimer.php?id=<?php echo $mariage['id']; ?>" 
                                      onclick="return confirm('Voulez-vous vraiment supprimer cet enregistrement ?');">
                                        Supprimer
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Aucun mariage enregistré.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchMariage').addEventListener('keyup', function () {
    let motcle = this.value;

    fetch('recherche_ajax.php?search=' + encodeURIComponent(motcle))
        .then(response => response.text())
        .then(data => {
            document.getElementById('resultats-mariages').innerHTML = data;
        });
});
</script>

<?php include '../includes/footer.php'; ?>