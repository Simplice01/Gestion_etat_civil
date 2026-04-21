<?php
require_once '../includes/session_check.php';
require_once '../config/database.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id = $_GET['id'] ?? null;
$role = $_SESSION['role'];
$arrondissement_id = $_SESSION['arrondissement_id'];

if (!$id) {
    die("Identifiant invalide");
}

/* =========================
   RÉCUPÉRATION DE L'ACTE
========================= */
if ($role === 'administrateur') {
    $sql = "SELECT m.*, a.nom AS nom_arrondissement
            FROM mariages m
            LEFT JOIN arrondissements a ON m.arrondissement_id = a.id
            WHERE m.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
} else {
    $sql = "SELECT m.*, a.nom AS nom_arrondissement
            FROM mariages m
            LEFT JOIN arrondissements a ON m.arrondissement_id = a.id
            WHERE m.id = ? AND m.arrondissement_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $arrondissement_id]);
}

$mariage = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mariage) {
    die("Enregistrement introuvable ou accès refusé");
}

/* =========================
   RÉCUPÉRATION DES TÉMOINS
========================= */
$stmtTemoins = $pdo->prepare("SELECT * FROM temoins_mariage WHERE mariage_id = ?");
$stmtTemoins->execute([$id]);
$temoins = $stmtTemoins->fetchAll(PDO::FETCH_ASSOC);

$blocTemoins = '';
if ($temoins) {
    foreach ($temoins as $temoin) {
        $blocTemoins .= '
        <div class="section">
            <span class="label">Témoin :</span> ' .
            htmlspecialchars($temoin['noms_temoin'] . ' ' . $temoin['prenoms_temoin']) .
            '
        </div>
        <div class="section">
            <span class="label">Profession du témoin :</span> ' .
            htmlspecialchars($temoin['profession_temoin']) .
            '
        </div>
        <div class="section">
            <span class="label">Domicile du témoin :</span> ' .
            htmlspecialchars($temoin['domicile_temoin']) .
            '
        </div>
        <div class="line"></div>
        ';
    }
}

/* =========================
   PRÉPARATION DES DONNÉES
========================= */
$numeroActe = $mariage['numero_acte'];
$dateGeneration = date('Y-m-d H:i:s');

$nomFichier = 'acte_mariage_' . $numeroActe . '_' . time() . '.pdf';
$dossierAbsolu = __DIR__ . '/../uploads/documents/';
$cheminRelatifBDD = 'uploads/documents/' . $nomFichier;
$cheminAbsoluFichier = $dossierAbsolu . $nomFichier;

if (!is_dir($dossierAbsolu)) {
    mkdir($dossierAbsolu, 0777, true);
}

/* =========================
   HTML DU DOCUMENT
========================= */
$html = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Acte de mariage</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            margin: 35px 40px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header .country {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header .motto {
            font-size: 12px;
            margin-top: 4px;
        }

        .header .mairie {
            font-size: 14px;
            font-weight: bold;
            margin-top: 18px;
            text-transform: uppercase;
        }

        .header .service {
            font-size: 12px;
            margin-top: 4px;
        }

        .titre-document {
            margin-top: 20px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .reference-box {
            margin-top: 25px;
            margin-bottom: 20px;
            border: 1px solid #000;
            padding: 10px 14px;
        }

        .content-box {
            border: 1px solid #000;
            padding: 18px 20px;
        }

        .section {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
        }

        .paragraph {
            text-align: justify;
            margin-top: 15px;
        }

        .signature {
            margin-top: 55px;
            text-align: right;
        }

        .signature p {
            margin: 4px 0;
        }

        .footer-note {
            margin-top: 40px;
            font-size: 11px;
            text-align: center;
            color: #333;
        }

        .line {
            margin: 18px 0;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="country">République du Bénin</div>
        <div class="motto">Fraternité - Justice - Travail</div>

        <div class="mairie">Mairie de Cotonou</div>
        <div class="service">Centre d\'état civil - ' . htmlspecialchars($mariage['nom_arrondissement']) . '</div>

        <div class="titre-document">Acte de mariage</div>
    </div>

    <div class="reference-box">
        <div><span class="label">Numéro d\'acte :</span> ' . htmlspecialchars($mariage['numero_acte']) . '</div>
        <div><span class="label">Arrondissement :</span> ' . htmlspecialchars($mariage['nom_arrondissement']) . '</div>
        <div><span class="label">Date de célébration :</span> ' . htmlspecialchars($mariage['date_celebration']) . '</div>
        <div><span class="label">Lieu de célébration :</span> ' . htmlspecialchars($mariage['lieu_celebration']) . '</div>
    </div>

    <div class="content-box">
        <div class="section"><span class="label">Nom de l\'époux :</span> ' . htmlspecialchars($mariage['nom_epoux']) . '</div>
        <div class="section"><span class="label">Prénom(s) de l\'époux :</span> ' . htmlspecialchars($mariage['prenoms_epoux']) . '</div>
        <div class="section"><span class="label">Date de naissance de l\'époux :</span> ' . htmlspecialchars($mariage['date_naissance_epoux']) . '</div>
        <div class="section"><span class="label">Lieu de naissance de l\'époux :</span> ' . htmlspecialchars($mariage['lieu_naissance_epoux']) . '</div>
        <div class="section"><span class="label">Profession de l\'époux :</span> ' . htmlspecialchars($mariage['profession_epoux']) . '</div>
        <div class="section"><span class="label">Domicile de l\'époux :</span> ' . htmlspecialchars($mariage['domicile_epoux']) . '</div>

        <div class="line"></div>

        <div class="section"><span class="label">Nom de l\'épouse :</span> ' . htmlspecialchars($mariage['nom_epouse']) . '</div>
        <div class="section"><span class="label">Prénom(s) de l\'épouse :</span> ' . htmlspecialchars($mariage['prenoms_epouse']) . '</div>
        <div class="section"><span class="label">Date de naissance de l\'épouse :</span> ' . htmlspecialchars($mariage['date_naissance_epouse']) . '</div>
        <div class="section"><span class="label">Lieu de naissance de l\'épouse :</span> ' . htmlspecialchars($mariage['lieu_naissance_epouse']) . '</div>
        <div class="section"><span class="label">Profession de l\'épouse :</span> ' . htmlspecialchars($mariage['profession_epouse']) . '</div>
        <div class="section"><span class="label">Domicile de l\'épouse :</span> ' . htmlspecialchars($mariage['domicile_epouse']) . '</div>

        <div class="line"></div>

        <div class="section"><span class="label">Nom du parent de l\'époux :</span> ' . htmlspecialchars($mariage['nom_parent_epoux']) . '</div>
        <div class="section"><span class="label">Prénom(s) du parent de l\'époux :</span> ' . htmlspecialchars($mariage['prenom_parent_epoux']) . '</div>
        <div class="section"><span class="label">Profession du parent de l\'époux :</span> ' . htmlspecialchars($mariage['profession_parent_epoux']) . '</div>
        <div class="section"><span class="label">Domicile du parent de l\'époux :</span> ' . htmlspecialchars($mariage['domicile_parent_epoux']) . '</div>

        <div class="line"></div>

        <div class="section"><span class="label">Nom du parent de l\'épouse :</span> ' . htmlspecialchars($mariage['nom_parent_epouse']) . '</div>
        <div class="section"><span class="label">Prénom(s) du parent de l\'épouse :</span> ' . htmlspecialchars($mariage['prenom_parent_epouse']) . '</div>
        <div class="section"><span class="label">Profession du parent de l\'épouse :</span> ' . htmlspecialchars($mariage['profession_parent_epouse']) . '</div>
        <div class="section"><span class="label">Domicile du parent de l\'épouse :</span> ' . htmlspecialchars($mariage['domicile_parent_epouse']) . '</div>

        <div class="line"></div>

        <div class="section"><span class="label">Régime matrimonial :</span> ' . htmlspecialchars($mariage['regime_matrimonial']) . '</div>

        <div class="line"></div>

        ' . $blocTemoins . '

        <div class="paragraph">
            Le présent acte de mariage est établi sur la base des informations enregistrées dans le système
            de gestion de l\'état civil de la Mairie de Cotonou.
        </div>
    </div>

    <div class="signature">
        <p>Fait à Cotonou, le ' . date('d/m/Y') . '</p>
        <p><strong>L\'officier d\'état civil</strong></p>
        <br><br>
        <p>__________________________</p>
    </div>

    <div class="footer-note">
        Document généré automatiquement par le système numérique de gestion de l\'état civil.
    </div>

</body>
</html>
';

/* =========================
   GÉNÉRATION DU PDF
========================= */
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* =========================
   ENREGISTREMENT DU FICHIER
========================= */
file_put_contents($cheminAbsoluFichier, $dompdf->output());

/* =========================
   ENREGISTREMENT EN BASE
========================= */
$sqlDoc = "INSERT INTO documents_generes (
    type_document,
    reference_id,
    chemin_fichier,
    date_generation
) VALUES (?, ?, ?, ?)";

$stmtDoc = $pdo->prepare($sqlDoc);
$stmtDoc->execute([
    'mariage',
    $mariage['id'],
    $cheminRelatifBDD,
    $dateGeneration
]);

/* =========================
   AFFICHAGE DANS LE NAVIGATEUR
========================= */
$dompdf->stream($nomFichier, ["Attachment" => false]);
exit;