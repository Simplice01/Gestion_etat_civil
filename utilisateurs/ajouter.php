<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

if ($_SESSION['role'] !== 'administrateur') {
    die("Accès refusé");
}

$roles = $pdo->query("SELECT * FROM roles ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);
$arrondissements = $pdo->query("SELECT * FROM arrondissements ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Ajouter un utilisateur</h1>

    <form action="ajouter_traitement.php" method="POST">
        <div>
            <label>Nom</label>
            <input type="text" name="nom" required>
        </div>

        <div>
            <label>Prénom</label>
            <input type="text" name="prenom" required>
        </div>

        <div>
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" required>
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>
        </div>

        <div>
            <label>Rôle</label>
            <select name="role_id" required>
                <option value="">Choisir</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id']; ?>">
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
                    <option value="<?php echo $arrondissement['id']; ?>">
                        <?php echo htmlspecialchars($arrondissement['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Statut</label>
            <select name="statut" required>
                <option value="actif">Actif</option>
                <option value="inactif">Inactif</option>
            </select>
        </div>

        <button type="submit">Enregistrer</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>