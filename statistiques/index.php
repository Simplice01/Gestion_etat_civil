<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];
$utilisateur_id = $_SESSION['utilisateur_id'];

if (!in_array($role, ['administrateur', 'superviseur', 'analyste'])) {
    die("Accès refusé");
}

/* =========================
   STATISTIQUES GLOBALES
========================= */

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

$total_general = $total_naissances + $total_mariages + $total_deces;

/* =========================
   TOTAL PAR ARRONDISSEMENT
========================= */

if ($role === 'administrateur') {
    $sql_arr = "
        SELECT 
            a.nom,
            COALESCE(n.total_naissances, 0) AS total_naissances,
            COALESCE(m.total_mariages, 0) AS total_mariages,
            COALESCE(d.total_deces, 0) AS total_deces
        FROM arrondissements a
        LEFT JOIN (
            SELECT arrondissement_id, COUNT(*) AS total_naissances
            FROM naissances
            GROUP BY arrondissement_id
        ) n ON a.id = n.arrondissement_id
        LEFT JOIN (
            SELECT arrondissement_id, COUNT(*) AS total_mariages
            FROM mariages
            GROUP BY arrondissement_id
        ) m ON a.id = m.arrondissement_id
        LEFT JOIN (
            SELECT arrondissement_id, COUNT(*) AS total_deces
            FROM deces
            GROUP BY arrondissement_id
        ) d ON a.id = d.arrondissement_id
        ORDER BY a.nom ASC
    ";
    $stmt_arr = $pdo->query($sql_arr);
    $stats_arrondissements = $stmt_arr->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql_arr = "
        SELECT 
            a.nom,
            COALESCE(n.total_naissances, 0) AS total_naissances,
            COALESCE(m.total_mariages, 0) AS total_mariages,
            COALESCE(d.total_deces, 0) AS total_deces
        FROM arrondissements a
        LEFT JOIN (
            SELECT arrondissement_id, COUNT(*) AS total_naissances
            FROM naissances
            GROUP BY arrondissement_id
        ) n ON a.id = n.arrondissement_id
        LEFT JOIN (
            SELECT arrondissement_id, COUNT(*) AS total_mariages
            FROM mariages
            GROUP BY arrondissement_id
        ) m ON a.id = m.arrondissement_id
        LEFT JOIN (
            SELECT arrondissement_id, COUNT(*) AS total_deces
            FROM deces
            GROUP BY arrondissement_id
        ) d ON a.id = d.arrondissement_id
        WHERE a.id = ?
    ";
    $stmt_arr = $pdo->prepare($sql_arr);
    $stmt_arr->execute([$arrondissement_id]);
    $stats_arrondissements = $stmt_arr->fetchAll(PDO::FETCH_ASSOC);
}

/* =========================
   TOTAL PAR UTILISATEUR
========================= */

if ($role === 'administrateur') {
    $sql_users = "
        SELECT 
            u.nom,
            u.prenom,
            COALESCE(n.total_naissances, 0) AS total_naissances,
            COALESCE(m.total_mariages, 0) AS total_mariages,
            COALESCE(d.total_deces, 0) AS total_deces
        FROM utilisateurs u
        LEFT JOIN (
            SELECT utilisateur_id, COUNT(*) AS total_naissances
            FROM naissances
            GROUP BY utilisateur_id
        ) n ON u.id = n.utilisateur_id
        LEFT JOIN (
            SELECT utilisateur_id, COUNT(*) AS total_mariages
            FROM mariages
            GROUP BY utilisateur_id
        ) m ON u.id = m.utilisateur_id
        LEFT JOIN (
            SELECT utilisateur_id, COUNT(*) AS total_deces
            FROM deces
            GROUP BY utilisateur_id
        ) d ON u.id = d.utilisateur_id
        ORDER BY u.nom ASC, u.prenom ASC
    ";
    $stmt_users = $pdo->query($sql_users);
    $stats_utilisateurs = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql_users = "
        SELECT 
            u.nom,
            u.prenom,
            COALESCE(n.total_naissances, 0) AS total_naissances,
            COALESCE(m.total_mariages, 0) AS total_mariages,
            COALESCE(d.total_deces, 0) AS total_deces
        FROM utilisateurs u
        LEFT JOIN (
            SELECT utilisateur_id, COUNT(*) AS total_naissances
            FROM naissances
            GROUP BY utilisateur_id
        ) n ON u.id = n.utilisateur_id
        LEFT JOIN (
            SELECT utilisateur_id, COUNT(*) AS total_mariages
            FROM mariages
            GROUP BY utilisateur_id
        ) m ON u.id = m.utilisateur_id
        LEFT JOIN (
            SELECT utilisateur_id, COUNT(*) AS total_deces
            FROM deces
            GROUP BY utilisateur_id
        ) d ON u.id = d.utilisateur_id
        WHERE u.arrondissement_id = ?
        ORDER BY u.nom ASC, u.prenom ASC
    ";
    $stmt_users = $pdo->prepare($sql_users);
    $stmt_users->execute([$arrondissement_id]);
    $stats_utilisateurs = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
}

/* =========================
   TOTAL PAR PÉRIODE
========================= */

if ($role === 'administrateur') {
    $sql_periode = "
        SELECT 'Naissances' AS type_acte, DATE(created_at) AS date_enregistrement, COUNT(*) AS total
        FROM naissances
        GROUP BY DATE(created_at)

        UNION ALL

        SELECT 'Mariages' AS type_acte, DATE(created_at) AS date_enregistrement, COUNT(*) AS total
        FROM mariages
        GROUP BY DATE(created_at)

        UNION ALL

        SELECT 'Décès' AS type_acte, DATE(created_at) AS date_enregistrement, COUNT(*) AS total
        FROM deces
        GROUP BY DATE(created_at)

        ORDER BY date_enregistrement DESC
    ";
    $stmt_periode = $pdo->query($sql_periode);
    $stats_periode = $stmt_periode->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql_periode = "
        SELECT 'Naissances' AS type_acte, DATE(created_at) AS date_enregistrement, COUNT(*) AS total
        FROM naissances
        WHERE arrondissement_id = ?
        GROUP BY DATE(created_at)

        UNION ALL

        SELECT 'Mariages' AS type_acte, DATE(created_at) AS date_enregistrement, COUNT(*) AS total
        FROM mariages
        WHERE arrondissement_id = ?
        GROUP BY DATE(created_at)

        UNION ALL

        SELECT 'Décès' AS type_acte, DATE(created_at) AS date_enregistrement, COUNT(*) AS total
        FROM deces
        WHERE arrondissement_id = ?
        GROUP BY DATE(created_at)

        ORDER BY date_enregistrement DESC
    ";
    $stmt_periode = $pdo->prepare($sql_periode);
    $stmt_periode->execute([$arrondissement_id, $arrondissement_id, $arrondissement_id]);
    $stats_periode = $stmt_periode->fetchAll(PDO::FETCH_ASSOC);
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>

<div class="main-content">
    <h1>Statistiques</h1>

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

        <div class="card">
            <h3>Total général</h3>
            <p><?php echo $total_general; ?></p>
        </div>
    </div>

    <h2>Total par arrondissement</h2>
    <table>
        <thead>
            <tr>
                <th>Arrondissement</th>
                <th>Naissances</th>
                <th>Mariages</th>
                <th>Décès</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($stats_arrondissements) > 0): ?>
                <?php foreach ($stats_arrondissements as $stat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($stat['nom']); ?></td>
                        <td><?php echo $stat['total_naissances']; ?></td>
                        <td><?php echo $stat['total_mariages']; ?></td>
                        <td><?php echo $stat['total_deces']; ?></td>
                        <td><?php echo $stat['total_naissances'] + $stat['total_mariages'] + $stat['total_deces']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Aucune donnée disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Total par utilisateur</h2>
    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Naissances</th>
                <th>Mariages</th>
                <th>Décès</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($stats_utilisateurs) > 0): ?>
                <?php foreach ($stats_utilisateurs as $stat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($stat['nom'] . ' ' . $stat['prenom']); ?></td>
                        <td><?php echo $stat['total_naissances']; ?></td>
                        <td><?php echo $stat['total_mariages']; ?></td>
                        <td><?php echo $stat['total_deces']; ?></td>
                        <td><?php echo $stat['total_naissances'] + $stat['total_mariages'] + $stat['total_deces']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Aucune donnée disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Total par période</h2>
    <table>
        <thead>
            <tr>
                <th>Type d'acte</th>
                <th>Date</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($stats_periode) > 0): ?>
                <?php foreach ($stats_periode as $stat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($stat['type_acte']); ?></td>
                        <td><?php echo htmlspecialchars($stat['date_enregistrement']); ?></td>
                        <td><?php echo $stat['total']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Aucune donnée disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>