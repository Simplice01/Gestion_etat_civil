<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$search = trim($_GET['search'] ?? '');
$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

$params = [];
$where = [];

$sql = "SELECT d.*, a.nom AS nom_arrondissement
        FROM deces d
        LEFT JOIN arrondissements a ON d.arrondissement_id = a.id";

if ($role !== 'administrateur') {
    $where[] = "d.arrondissement_id = ?";
    $params[] = $arrondissement_id;
}

if (!empty($search)) {
    $where[] = "(
        d.numero_acte LIKE ?
        OR d.noms_defunt LIKE ?
        OR d.prenoms_defunt LIKE ?
        OR d.lieu_deces LIKE ?
        OR d.nom_declarant LIKE ?
        OR d.prenoms_declarant LIKE ?
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

$sql .= " ORDER BY d.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$deces = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
    <thead>
        <tr>
            <th>N° Acte</th>
            <th>Nom du défunt</th>
            <th>Date décès</th>
            <th>Lieu décès</th>
            <th>Déclarant</th>
            <th>Arrondissement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($deces) > 0): ?>
            <?php foreach ($deces as $deces_item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($deces_item['numero_acte']); ?></td>
                    <td><?php echo htmlspecialchars($deces_item['noms_defunt'] . ' ' . $deces_item['prenoms_defunt']); ?></td>
                    <td><?php echo htmlspecialchars($deces_item['date_deces']); ?></td>
                    <td><?php echo htmlspecialchars($deces_item['lieu_deces']); ?></td>
                    <td><?php echo htmlspecialchars($deces_item['nom_declarant'] . ' ' . $deces_item['prenoms_declarant']); ?></td>
                    <td><?php echo htmlspecialchars($deces_item['nom_arrondissement']); ?></td>
                    <td>
                        <a class="action-link" href="details.php?id=<?php echo $deces_item['id']; ?>">Voir</a>

                        <?php if ($_SESSION['role'] !== 'analyste'): ?>
                            <a class="action-link" href="modifier.php?id=<?php echo $deces_item['id']; ?>">Modifier</a>
                            <a class="action-link delete-link" href="supprimer.php?id=<?php echo $deces_item['id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet enregistrement ?');">Supprimer</a>
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