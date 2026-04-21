<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$search = trim($_GET['search'] ?? '');
$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

$params = [];
$where = [];

$sql = "SELECT m.*, a.nom AS nom_arrondissement
        FROM mariages m
        LEFT JOIN arrondissements a ON m.arrondissement_id = a.id";

if ($role !== 'administrateur') {
    $where[] = "m.arrondissement_id = ?";
    $params[] = $arrondissement_id;
}

if (!empty($search)) {
    $where[] = "(
        m.numero_acte LIKE ?
        OR m.nom_epoux LIKE ?
        OR m.prenoms_epoux LIKE ?
        OR m.nom_epouse LIKE ?
        OR m.prenoms_epouse LIKE ?
        OR m.lieu_celebration LIKE ?
    )";

    $searchLike = "%" . $search . "%";
    $params[] = $searchLike;
    $params[] = $searchLike;
    $params[] = $searchLike;
    $params[] = $searchLike;
    $params[] = $searchLike;
    $params[] = $searchLike;
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY m.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$mariages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
    <thead>
        <tr>
            <th>N° Acte</th>
            <th>Époux</th>
            <th>Épouse</th>
            <th>Date célébration</th>
            <th>Lieu célébration</th>
            <th>Arrondissement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($mariages) > 0): ?>
            <?php foreach ($mariages as $mariage): ?>
                <tr>
                    <td><?php echo htmlspecialchars($mariage['numero_acte']); ?></td>
                    <td><?php echo htmlspecialchars($mariage['nom_epoux'] . ' ' . $mariage['prenoms_epoux']); ?></td>
                    <td><?php echo htmlspecialchars($mariage['nom_epouse'] . ' ' . $mariage['prenoms_epouse']); ?></td>
                    <td><?php echo htmlspecialchars($mariage['date_celebration']); ?></td>
                    <td><?php echo htmlspecialchars($mariage['lieu_celebration']); ?></td>
                    <td><?php echo htmlspecialchars($mariage['nom_arrondissement']); ?></td>
                    <td>
                        <a class="action-link" href="details.php?id=<?php echo $mariage['id']; ?>">Voir</a>

                        <?php if ($_SESSION['role'] !== 'analyste'): ?>
                            <a class="action-link" href="modifier.php?id=<?php echo $mariage['id']; ?>">Modifier</a>
                            <a class="action-link delete-link" href="supprimer.php?id=<?php echo $mariage['id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet enregistrement ?');">Supprimer</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Aucun résultat trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>