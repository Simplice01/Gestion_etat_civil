<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

if ($_SESSION['role'] === 'analyste') {
    die("Accès refusé");
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Ajouter un décès</h1>

    <form action="ajouter_traitement.php" method="POST">
        <div>
            <label>Noms du défunt</label>
            <input type="text" name="noms_defunt" required>
        </div>

        <div>
            <label>Prénoms du défunt</label>
            <input type="text" name="prenoms_defunt" required>
        </div>

        <div>
            <label>Date de naissance du défunt</label>
            <input type="date" name="date_naissance_defunt">
        </div>

        <div>
            <label>Lieu de naissance du défunt</label>
            <input type="text" name="lieu_naissance_defunt">
        </div>

        <div>
            <label>Profession du défunt</label>
            <input type="text" name="profession_defunt">
        </div>

        <div>
            <label>Dernier domicile du défunt</label>
            <input type="text" name="dernier_domicile_defunt">
        </div>

        <hr>

        <div>
            <label>Date de décès</label>
            <input type="date" name="date_deces" required>
        </div>

        <div>
            <label>Heure de décès</label>
            <input type="time" name="heure_deces">
        </div>

        <div>
            <label>Lieu de décès</label>
            <input type="text" name="lieu_deces" required>
        </div>

        <hr>

        <div>
            <label>Nom du déclarant</label>
            <input type="text" name="nom_declarant" required>
        </div>

        <div>
            <label>Prénoms du déclarant</label>
            <input type="text" name="prenoms_declarant" required>
        </div>

        <div>
            <label>Lien de parenté du déclarant</label>
            <input type="text" name="lien_parente_declarant">
        </div>

        <div>
            <label>Documents justificatifs</label>
            <textarea name="documents_justificatifs" rows="4"></textarea>
        </div>

        <button type="submit">Enregistrer</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>