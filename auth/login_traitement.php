<?php
session_start();

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$mot_de_passe = $_POST['mot_de_passe'] ?? '';

$sql = "SELECT utilisateurs.*, roles.nom AS role_nom
        FROM utilisateurs
        INNER JOIN roles ON utilisateurs.role_id = roles.id
        WHERE utilisateurs.username = ?
          AND utilisateurs.statut = 'actif'
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {

    $_SESSION['utilisateur_id'] = $user['id'];
    $_SESSION['nom'] = $user['nom'];
    $_SESSION['prenom'] = $user['prenom'];
    $_SESSION['role'] = $user['role_nom'];
    $_SESSION['arrondissement_id'] = $user['arrondissement_id'];

    header("Location: ../dashboard/index.php");
    exit;

} else {
    header("Location: login.php?erreur=1");
    exit;
}
?>