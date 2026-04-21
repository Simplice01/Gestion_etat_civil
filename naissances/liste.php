<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

if ($role === 'administrateur') {
    $sql = "SELECT n.*, a.nom AS nom_arrondissement
            FROM naissances n
            LEFT JOIN arrondissements a ON n.arrondissement_id = a.id
            ORDER BY n.id DESC";
    $stmt = $pdo->query($sql);
} else {
    $sql = "SELECT n.*, a.nom AS nom_arrondissement
            FROM naissances n
            LEFT JOIN arrondissements a ON n.arrondissement_id = a.id
            WHERE n.arrondissement_id = ?
            ORDER BY n.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$arrondissement_id]);
}

$naissances = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Liste des naissances</h1>

    <?php if ($_SESSION['role'] !== 'analyste'): ?>
        <p>
            <a href="ajouter.php" class="btn-link">Ajouter une naissance</a>
        </p>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <input 
            type="text" 
            id="searchNaissance" 
            placeholder="Rechercher par numéro d'acte, nom, prénom, lieu..."
            style="width: 100%; max-width: 500px;"
        >
    </div>

    <div id="resultats-naissances">
        <table>
            <thead>
                <tr>
                    <th>N° Acte</th>
                    <th>Nom enfant</th>
                    <th>Prénoms enfant</th>
                    <th>Sexe</th>
                    <th>Date naissance</th>
                    <th>Lieu naissance</th>
                    <th>Arrondissement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($naissances) > 0): ?>
                    <?php foreach ($naissances as $naissance): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($naissance['numero_acte']); ?></td>
                            <td><?php echo htmlspecialchars($naissance['noms_enfant']); ?></td>
                            <td><?php echo htmlspecialchars($naissance['prenoms_enfant']); ?></td>
                            <td><?php echo htmlspecialchars($naissance['sexe_enfant']); ?></td>
                            <td><?php echo htmlspecialchars($naissance['date_naissance_enfant']); ?></td>
                            <td><?php echo htmlspecialchars($naissance['lieu_naissance_enfant']); ?></td>
                            <td><?php echo htmlspecialchars($naissance['nom_arrondissement']); ?></td>
                            <td class="actions-cell">
                                <a class="btn-action btn-view" href="details.php?id=<?php echo $naissance['id']; ?>">
                                    Voir
                                </a>

                                <?php if ($_SESSION['role'] !== 'analyste'): ?>
                                    <a class="btn-action btn-edit" href="modifier.php?id=<?php echo $naissance['id']; ?>">
                                        Modifier
                                    </a>

                                    <a class="btn-action btn-delete" 
                                      href="supprimer.php?id=<?php echo $naissance['id']; ?>" 
                                      onclick="return confirm('Voulez-vous vraiment supprimer cet enregistrement ?');">
                                        Supprimer
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Aucune naissance enregistrée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('searchNaissance').addEventListener('keyup', function () {
    let motcle = this.value;

    fetch('recherche_ajax.php?search=' + encodeURIComponent(motcle))
        .then(response => response.text())
        .then(data => {
            document.getElementById('resultats-naissances').innerHTML = data;
        });
});
</script>

<?php include '../includes/footer.php'; ?>