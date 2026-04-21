<?php
session_start();

if (isset($_SESSION['utilisateur_id'])) {
    header("Location: ../dashboard/index.php");
    exit;
}

include '../includes/header.php';
?>

<div class="login-container">
    <h2>Connexion</h2>

    <?php if (isset($_GET['erreur'])): ?>
        <p class="error-message">Nom d'utilisateur ou mot de passe incorrect.</p>
    <?php endif; ?>

    <form action="login_traitement.php" method="POST" class="login-form">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" required>
        </div>

        <div class="form-group">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" required>
        </div>

        <button type="submit">Se connecter</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>