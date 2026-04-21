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
    <h1>Ajouter un mariage</h1>

    <form action="ajouter_traitement.php" method="POST">
        <div>
            <label>Nom époux</label>
            <input type="text" name="nom_epoux" required>
        </div>

        <div>
            <label>Prénoms époux</label>
            <input type="text" name="prenoms_epoux" required>
        </div>

        <div>
            <label>Date naissance époux</label>
            <input type="date" name="date_naissance_epoux">
        </div>

        <div>
            <label>Lieu naissance époux</label>
            <input type="text" name="lieu_naissance_epoux">
        </div>

        <div>
            <label>NPI époux</label>
            <input type="text" name="npi_epoux">
        </div>

        <div>
            <label>Profession époux</label>
            <input type="text" name="profession_epoux">
        </div>

        <div>
            <label>Domicile époux</label>
            <input type="text" name="domicile_epoux">
        </div>

        <hr>

        <div>
            <label>Nom épouse</label>
            <input type="text" name="nom_epouse" required>
        </div>

        <div>
            <label>Prénoms épouse</label>
            <input type="text" name="prenoms_epouse" required>
        </div>

        <div>
            <label>Date naissance épouse</label>
            <input type="date" name="date_naissance_epouse">
        </div>

        <div>
            <label>Lieu naissance épouse</label>
            <input type="text" name="lieu_naissance_epouse">
        </div>

        <div>
            <label>NPI épouse</label>
            <input type="text" name="npi_epouse">
        </div>

        <div>
            <label>Profession épouse</label>
            <input type="text" name="profession_epouse">
        </div>

        <div>
            <label>Domicile épouse</label>
            <input type="text" name="domicile_epouse">
        </div>

        <hr>

        <div>
            <label>Nom parent époux</label>
            <input type="text" name="nom_parent_epoux">
        </div>

        <div>
            <label>Prénom parent époux</label>
            <input type="text" name="prenom_parent_epoux">
        </div>

        <div>
            <label>Profession parent époux</label>
            <input type="text" name="profession_parent_epoux">
        </div>

        <div>
            <label>Domicile parent époux</label>
            <input type="text" name="domicile_parent_epoux">
        </div>

        <div>
            <label>Nom parent épouse</label>
            <input type="text" name="nom_parent_epouse">
        </div>

        <div>
            <label>Prénom parent épouse</label>
            <input type="text" name="prenom_parent_epouse">
        </div>

        <div>
            <label>Profession parent épouse</label>
            <input type="text" name="profession_parent_epouse">
        </div>

        <div>
            <label>Domicile parent épouse</label>
            <input type="text" name="domicile_parent_epouse">
        </div>

        <hr>

        <div>
            <label>Nom témoin</label>
            <input type="text" name="noms_temoin">
        </div>

        <div>
            <label>Prénoms témoin</label>
            <input type="text" name="prenoms_temoin">
        </div>

        <div>
            <label>Profession témoin</label>
            <input type="text" name="profession_temoin">
        </div>

        <div>
            <label>Domicile témoin</label>
            <input type="text" name="domicile_temoin">
        </div>

        <hr>

        <div>
            <label>Date célébration</label>
            <input type="date" name="date_celebration" required>
        </div>

        <div>
            <label>Lieu célébration</label>
            <input type="text" name="lieu_celebration" required>
        </div>

        <div>
            <label>Régime matrimonial</label>
            <select name="regime_matrimonial">
                <option value="">Choisir</option>
                <option value="communauté">Communauté</option>
                <option value="séparation">Séparation</option>
            </select>
        </div>

        <button type="submit">Enregistrer</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>