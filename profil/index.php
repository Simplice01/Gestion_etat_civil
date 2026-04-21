<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$utilisateur_id = $_SESSION['utilisateur_id'];

$sql = "SELECT u.*, r.nom AS role_nom, a.nom AS arrondissement_nom
        FROM utilisateurs u
        INNER JOIN roles r ON u.role_id = r.id
        LEFT JOIN arrondissements a ON u.arrondissement_id = a.id
        WHERE u.id = ?
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([$utilisateur_id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Utilisateur introuvable.");
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>


<div class="main-content">
    <h1>Mon profil</h1>

    <div class="profile-box">
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($utilisateur['nom']); ?></p>
        <p><strong>Prénom :</strong> <?php echo htmlspecialchars($utilisateur['prenom']); ?></p>
        <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($utilisateur['username']); ?></p>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($utilisateur['email']); ?></p>
        <p><strong>Rôle :</strong> <?php echo htmlspecialchars($utilisateur['role_nom']); ?></p>
        <p><strong>Arrondissement :</strong> <?php echo htmlspecialchars($utilisateur['arrondissement_nom'] ?? '-'); ?></p>
        <p><strong>Statut :</strong> <?php echo htmlspecialchars($utilisateur['statut']); ?></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>