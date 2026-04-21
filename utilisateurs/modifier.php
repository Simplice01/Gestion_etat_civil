<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

if ($_SESSION['role'] !== 'administrateur') {
    die("Accès refusé");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Identifiant invalide");
}

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Utilisateur introuvable");
}

$roles = $pdo->query("SELECT * FROM roles ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
$arrondissements = $pdo->query("SELECT * FROM arrondissements ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Modifier un utilisateur</h1>

    <form action="modifier_traitement.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $utilisateur['id']; ?>">

        <div>
            <label>Nom</label>
            <input type="text" name="nom" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" required>
        </div>

        <div>
            <label>Prénom</label>
            <input type="text" name="prenom" value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>" required>
        </div>

        <div>
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($utilisateur['username']); ?>" required>
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($utilisateur['email']); ?>" required>
        </div>

        <div>
            <label>Nouveau mot de passe</label>
            <input type="password" name="mot_de_passe">
        </div>

        <div>
            <label>Rôle</label>
            <select name="role_id" required>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id']; ?>" <?php echo ($utilisateur['role_id'] == $role['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($role['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Arrondissement</label>
            <select name="arrondissement_id">
                <option value="">Choisir</option>
                <?php foreach ($arrondissements as $arrondissement): ?>
                    <option value="<?php echo $arrondissement['id']; ?>" <?php echo ($utilisateur['arrondissement_id'] == $arrondissement['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($arrondissement['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Statut</label>
            <select name="statut" required>
                <option value="actif" <?php echo ($utilisateur['statut'] === 'actif') ? 'selected' : ''; ?>>Actif</option>
                <option value="inactif" <?php echo ($utilisateur['statut'] === 'inactif') ? 'selected' : ''; ?>>Inactif</option>
            </select>
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>