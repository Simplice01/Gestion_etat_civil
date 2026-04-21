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
    <h1>Ajouter une naissance</h1>

    <form action="ajouter_traitement.php" method="POST">

        <div>
            <label>Noms de l'enfant</label>
            <input type="text" name="noms_enfant" required>
        </div>

        <div>
            <label>Prénoms de l'enfant</label>
            <input type="text" name="prenoms_enfant" required>
        </div>

        <div>
            <label>Sexe</label>
            <select name="sexe_enfant" required>
                <option value="">Choisir</option>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
            </select>
        </div>

        <div>
            <label>Date de naissance</label>
            <input type="date" name="date_naissance_enfant" required>
        </div>

        <div>
            <label>Lieu de naissance</label>
            <input type="text" name="lieu_naissance_enfant" required>
        </div>

        <hr>

        <div>
            <label>Nom mère</label>
            <input type="text" name="nom_mere" required>
        </div>

        <div>
            <label>Prénom mère</label>
            <input type="text" name="prenom_mere" required>
        </div>

        <div>
            <label>Date de naissance mère</label>
            <input type="date" name="date_naissance_mere">
        </div>

        <div>
            <label>Lieu de naissance mère</label>
            <input type="text" name="lieu_naissance_mere">
        </div>

        <div>
            <label>Profession mère</label>
            <input type="text" name="profession_mere">
        </div>

        <div>
            <label>Domicile mère</label>
            <input type="text" name="domicile_mere">
        </div>

        <div>
            <label>Nationalité mère</label>
            <input type="text" name="nationalite_mere">
        </div>

        <hr>

        <div>
            <label>Nom père</label>
            <input type="text" name="nom_pere">
        </div>

        <div>
            <label>Prénom père</label>
            <input type="text" name="prenom_pere">
        </div>

        <div>
            <label>Date de naissance père</label>
            <input type="date" name="date_naissance_pere">
        </div>

        <div>
            <label>Lieu de naissance père</label>
            <input type="text" name="lieu_naissance_pere">
        </div>

        <div>
            <label>Profession père</label>
            <input type="text" name="profession_pere">
        </div>

        <div>
            <label>Domicile père</label>
            <input type="text" name="domicile_pere">
        </div>

        <div>
            <label>Nationalité père</label>
            <input type="text" name="nationalite_pere">
        </div>

        <div>
            <label>NPI parent(Père ou mère)</label>
            <input type="text" name="npi_pere">
        </div>

        <div>
            <label>Date de déclaration</label>
            <input type="date" name="date_declaration" required>
        </div>

        <br>
        <button type="submit">Enregistrer</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>