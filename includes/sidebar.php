<?php if (isset($_SESSION['utilisateur_id'])): ?>
<div class="sidebar">

    <div class="sidebar-title">
        Gestion de l'état civil
    </div>

    <ul class="sidebar-menu">

        <li>
            <a href="/Gestion_etat_civil/dashboard/index.php">Tableau de bord</a>
        </li>

        <li>
            <a href="/Gestion_etat_civil/naissances/liste.php">Naissances</a>
        </li>

        <li>
            <a href="/Gestion_etat_civil/mariages/liste.php">Mariages</a>
        </li>

        <li>
            <a href="/Gestion_etat_civil/deces/liste.php">Décès</a>
        </li>

        <!-- PDF -->
        <li class="menu-dropdown">
            <div class="dropdown-toggle" onclick="toggleMenu('pdfMenu')">
                Documents PDF
                <span class="arrow">▾</span>
            </div>

            <ul class="submenu" id="pdfMenu">
                <li><a href="/Gestion_etat_civil/documents_pdf/naissances.php">Actes de naissance</a></li>
                <li><a href="/Gestion_etat_civil/documents_pdf/mariages.php">Actes de mariage</a></li>
                <li><a href="/Gestion_etat_civil/documents_pdf/deces.php">Actes de décès</a></li>
            </ul>
        </li>

        <li>
            <a href="/Gestion_etat_civil/statistiques/index.php">Statistiques</a>
        </li>

        <?php if ($_SESSION['role'] === 'administrateur'): ?>
            <li>
                <a href="/Gestion_etat_civil/utilisateurs/liste.php">Gestion des utilisateurs</a>
            </li>
        <?php endif; ?>

    </ul>
</div>

<script>
function toggleMenu(id) {
    let menu = document.getElementById(id);
    menu.classList.toggle('open');
}
</script>

<?php endif; ?>