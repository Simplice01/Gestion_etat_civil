<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

if ($role === 'administrateur') {
    $total_naissances = $pdo->query("SELECT COUNT(*) FROM naissances")->fetchColumn();
    $total_mariages   = $pdo->query("SELECT COUNT(*) FROM mariages")->fetchColumn();
    $total_deces      = $pdo->query("SELECT COUNT(*) FROM deces")->fetchColumn();
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM naissances WHERE arrondissement_id = ?");
    $stmt->execute([$arrondissement_id]);
    $total_naissances = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM mariages WHERE arrondissement_id = ?");
    $stmt->execute([$arrondissement_id]);
    $total_mariages = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM deces WHERE arrondissement_id = ?");
    $stmt->execute([$arrondissement_id]);
    $total_deces = $stmt->fetchColumn();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Tableau de bord</h1>

    <div class="cards">
        <div class="card">
            <h3>Total naissances</h3>
            <p><?php echo $total_naissances; ?></p>
        </div>

        <div class="card">
            <h3>Total mariages</h3>
            <p><?php echo $total_mariages; ?></p>
        </div>

        <div class="card">
            <h3>Total décès</h3>
            <p><?php echo $total_deces; ?></p>
        </div>
    </div>

    <div class="welcome-box">
        <p>Bienvenue, <?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']; ?>.</p>
        <p>Rôle : <?php echo $_SESSION['role']; ?></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>