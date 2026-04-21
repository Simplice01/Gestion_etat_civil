<?php if (isset($_SESSION['utilisateur_id'])): ?>
<div class="navbar">
    <div class="navbar-left">
        <strong>Gestion État Civil - Cotonou</strong>
    </div>
    <div class="navbar-right">
      <a href="/Gestion_etat_civil/profil/"><span>
            <?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']; ?>
            (<?php echo $_SESSION['role']; ?>)
        </span></a>
        <a href="/Gestion_etat_civil/auth/logout.php" class="logout-icon" title="Déconnexion">
          <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</div>
<?php endif; ?>