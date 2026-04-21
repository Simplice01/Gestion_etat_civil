<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

if ($_SESSION['role'] !== 'administrateur') {
    die("Accès refusé");
}

$sql = "SELECT u.*, r.nom AS role_nom, a.nom AS arrondissement_nom
        FROM utilisateurs u
        INNER JOIN roles r ON u.role_id = r.id
        LEFT JOIN arrondissements a ON u.arrondissement_id = a.id
        ORDER BY u.id DESC";

$stmt = $pdo->query($sql);
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Liste des utilisateurs</h1>

    <p><a href="ajouter.php" class="btn-link">Ajouter un utilisateur</a></p>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Arrondissement</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($utilisateurs) > 0): ?>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['username']); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['role_nom']); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['arrondissement_nom'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['statut']); ?></td>
                        <td>
                            <a class="action-link" href="modifier.php?id=<?php echo $utilisateur['id']; ?>">Modifier</a>
                            <a class="action-link delete-link" href="supprimer.php?id=<?php echo $utilisateur['id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Aucun utilisateur enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>