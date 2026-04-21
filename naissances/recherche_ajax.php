<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';

$search = trim($_GET['search'] ?? '');
$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

$params = [];
$conditions = [];

if (!empty($search)) {
    $conditions[] = "(
        n.numero_acte LIKE ?
        OR n.noms_enfant LIKE ?
        OR n.prenoms_enfant LIKE ?
        OR n.lieu_naissance_enfant LIKE ?
        OR n.nom_mere LIKE ?
        OR n.prenom_mere LIKE ?
        OR n.nom_pere LIKE ?
        OR n.prenom_pere LIKE ?
    )";

    $searchLike = "%" . $search . "%";
    $params = array_fill(0, 8, $searchLike);
}

$sql = "SELECT n.*, a.nom AS nom_arrondissement
        FROM naissances n
        LEFT JOIN arrondissements a ON n.arrondissement_id = a.id";

$where = [];

if ($role !== 'administrateur') {
    $where[] = "n.arrondissement_id = ?";
    $params[] = $arrondissement_id;
}

if (!empty($conditions)) {
    $where[] = $conditions[0];
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY n.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$naissances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
    <thead>
        <tr>
            <th>N° Acte</th>
            <th>Nom enfant</th>
            <th>Prénoms enfant</th>
            <th>Sexe</th>
            <th>Date naissance</th>
            <th>Lieu naissance</th>
            <th>Arrondissement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($naissances) > 0): ?>
            <?php foreach ($naissances as $naissance): ?>
                <tr>
                    <td><?php echo htmlspecialchars($naissance['numero_acte']); ?></td>
                    <td><?php echo htmlspecialchars($naissance['noms_enfant']); ?></td>
                    <td><?php echo htmlspecialchars($naissance['prenoms_enfant']); ?></td>
                    <td><?php echo htmlspecialchars($naissance['sexe_enfant']); ?></td>
                    <td><?php echo htmlspecialchars($naissance['date_naissance_enfant']); ?></td>
                    <td><?php echo htmlspecialchars($naissance['lieu_naissance_enfant']); ?></td>
                    <td><?php echo htmlspecialchars($naissance['nom_arrondissement']); ?></td>
                    <td>
                        <a class="action-link" href="details.php?id=<?php echo $naissance['id']; ?>">Voir</a>

                        <?php if ($_SESSION['role'] !== 'analyste'): ?>
                            <a class="action-link" href="modifier.php?id=<?php echo $naissance['id']; ?>">Modifier</a>
                            <a class="action-link delete-link" href="supprimer.php?id=<?php echo $naissance['id']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet enregistrement ?');">Supprimer</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Aucun résultat trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>