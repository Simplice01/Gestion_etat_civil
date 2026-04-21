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
    $sql = "SELECT n.*, a.nom AS nom_arrondissement
            FROM naissances n
            LEFT JOIN arrondissements a ON n.arrondissement_id = a.id
            WHERE n.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
} else {
    $sql = "SELECT n.*, a.nom AS nom_arrondissement
            FROM naissances n
            LEFT JOIN arrondissements a ON n.arrondissement_id = a.id
            WHERE n.id = ? AND n.arrondissement_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $arrondissement_id]);
}

$naissance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$naissance) {
    die("Enregistrement introuvable ou accès refusé");
}

/* =========================
   PRÉPARATION DES DONNÉES
========================= */
$numeroActe = $naissance['numero_acte'];
$dateGeneration = date('Y-m-d H:i:s');

$nomFichier = 'acte_naissance_' . $numeroActe . '_' . time() . '.pdf';
$dossierRelatif = '../uploads/documents/';
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
    <title>Acte de naissance</title>
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
        <div class="service">Centre d\'état civil - ' . htmlspecialchars($naissance['nom_arrondissement']) . '</div>

        <div class="titre-document">Acte de naissance</div>
    </div>

    <div class="reference-box">
        <div><span class="label">Numéro d\'acte :</span> ' . htmlspecialchars($naissance['numero_acte']) . '</div>
        <div><span class="label">Arrondissement :</span> ' . htmlspecialchars($naissance['nom_arrondissement']) . '</div>
        <div><span class="label">Date de déclaration :</span> ' . htmlspecialchars($naissance['date_declaration']) . '</div>
    </div>

    <div class="content-box">
        <div class="section"><span class="label">Nom(s) de l\'enfant :</span> ' . htmlspecialchars($naissance['noms_enfant']) . '</div>
        <div class="section"><span class="label">Prénom(s) de l\'enfant :</span> ' . htmlspecialchars($naissance['prenoms_enfant']) . '</div>
        <div class="section"><span class="label">Sexe :</span> ' . htmlspecialchars($naissance['sexe_enfant']) . '</div>
        <div class="section"><span class="label">Date de naissance :</span> ' . htmlspecialchars($naissance['date_naissance_enfant']) . '</div>
        <div class="section"><span class="label">Lieu de naissance :</span> ' . htmlspecialchars($naissance['lieu_naissance_enfant']) . '</div>

        <div class="line"></div>

        <div class="section"><span class="label">Nom de la mère :</span> ' . htmlspecialchars($naissance['nom_mere']) . '</div>
        <div class="section"><span class="label">Prénom(s) de la mère :</span> ' . htmlspecialchars($naissance['prenom_mere']) . '</div>
        <div class="section"><span class="label">Date de naissance de la mère :</span> ' . htmlspecialchars($naissance['date_naissance_mere']) . '</div>
        <div class="section"><span class="label">Lieu de naissance de la mère :</span> ' . htmlspecialchars($naissance['lieu_naissance_mere']) . '</div>
        <div class="section"><span class="label">Profession de la mère :</span> ' . htmlspecialchars($naissance['profession_mere']) . '</div>
        <div class="section"><span class="label">Domicile de la mère :</span> ' . htmlspecialchars($naissance['domicile_mere']) . '</div>
        <div class="section"><span class="label">Nationalité de la mère :</span> ' . htmlspecialchars($naissance['nationalite_mere']) . '</div>

        <div class="line"></div>

        <div class="section"><span class="label">Nom du père :</span> ' . htmlspecialchars($naissance['nom_pere']) . '</div>
        <div class="section"><span class="label">Prénom(s) du père :</span> ' . htmlspecialchars($naissance['prenom_pere']) . '</div>
        <div class="section"><span class="label">Date de naissance du père :</span> ' . htmlspecialchars($naissance['date_naissance_pere']) . '</div>
        <div class="section"><span class="label">Lieu de naissance du père :</span> ' . htmlspecialchars($naissance['lieu_naissance_pere']) . '</div>
        <div class="section"><span class="label">Profession du père :</span> ' . htmlspecialchars($naissance['profession_pere']) . '</div>
        <div class="section"><span class="label">Domicile du père :</span> ' . htmlspecialchars($naissance['domicile_pere']) . '</div>
        <div class="section"><span class="label">Nationalité du père :</span> ' . htmlspecialchars($naissance['nationalite_pere']) . '</div>

        <div class="paragraph">
            Le présent acte de naissance est établi sur la base des informations enregistrées dans le système
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
    'naissance',
    $naissance['id'],
    $cheminRelatifBDD,
    $dateGeneration
]);

/* =========================
   AFFICHAGE DANS LE NAVIGATEUR
========================= */
$dompdf->stream($nomFichier, ["Attachment" => false]);
exit;