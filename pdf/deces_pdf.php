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
    $sql = "SELECT d.*, a.nom AS nom_arrondissement
            FROM deces d
            LEFT JOIN arrondissements a ON d.arrondissement_id = a.id
            WHERE d.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
} else {
    $sql = "SELECT d.*, a.nom AS nom_arrondissement
            FROM deces d
            LEFT JOIN arrondissements a ON d.arrondissement_id = a.id
            WHERE d.id = ? AND d.arrondissement_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $arrondissement_id]);
}

$deces = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$deces) {
    die("Enregistrement introuvable ou accès refusé");
}

/* =========================
   PRÉPARATION DES DONNÉES
========================= */
$numeroActe = $deces['numero_acte'];
$dateGeneration = date('Y-m-d H:i:s');

$nomFichier = 'acte_deces_' . $numeroActe . '_' . time() . '.pdf';
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
    <title>Acte de décès</title>
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
        <div class="service">Centre d\'état civil - ' . htmlspecialchars($deces['nom_arrondissement']) . '</div>

        <div class="titre-document">Acte de décès</div>
    </div>

    <div class="reference-box">
        <div><span class="label">Numéro d\'acte :</span> ' . htmlspecialchars($deces['numero_acte']) . '</div>
        <div><span class="label">Arrondissement :</span> ' . htmlspecialchars($deces['nom_arrondissement']) . '</div>
        <div><span class="label">Date de génération :</span> ' . date('d/m/Y H:i') . '</div>
    </div>

    <div class="content-box">
        <div class="section"><span class="label">Nom(s) du défunt :</span> ' . htmlspecialchars($deces['noms_defunt']) . '</div>
        <div class="section"><span class="label">Prénom(s) du défunt :</span> ' . htmlspecialchars($deces['prenoms_defunt']) . '</div>
        <div class="section"><span class="label">Date de naissance :</span> ' . htmlspecialchars($deces['date_naissance_defunt']) . '</div>
        <div class="section"><span class="label">Lieu de naissance :</span> ' . htmlspecialchars($deces['lieu_naissance_defunt']) . '</div>
        <div class="section"><span class="label">Profession :</span> ' . htmlspecialchars($deces['profession_defunt']) . '</div>
        <div class="section"><span class="label">Dernier domicile :</span> ' . htmlspecialchars($deces['dernier_domicile_defunt']) . '</div>

        <div class="line"></div>

        <div class="section"><span class="label">Date de décès :</span> ' . htmlspecialchars($deces['date_deces']) . '</div>
        <div class="section"><span class="label">Heure de décès :</span> ' . htmlspecialchars($deces['heure_deces']) . '</div>
        <div class="section"><span class="label">Lieu de décès :</span> ' . htmlspecialchars($deces['lieu_deces']) . '</div>

        <div class="line"></div>

        <div class="section"><span class="label">Nom du déclarant :</span> ' . htmlspecialchars($deces['nom_declarant']) . '</div>
        <div class="section"><span class="label">Prénom(s) du déclarant :</span> ' . htmlspecialchars($deces['prenoms_declarant']) . '</div>
        <div class="section"><span class="label">Lien de parenté :</span> ' . htmlspecialchars($deces['lien_parente_declarant']) . '</div>
        <div class="section"><span class="label">Documents justificatifs :</span> ' . nl2br(htmlspecialchars($deces['documents_justificatifs'])) . '</div>

        <div class="paragraph">
            Le présent acte de décès est établi sur la base des informations enregistrées dans le système
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
    'deces',
    $deces['id'],
    $cheminRelatifBDD,
    $dateGeneration
]);

/* =========================
   AFFICHAGE DANS LE NAVIGATEUR
========================= */
$dompdf->stream($nomFichier, ["Attachment" => false]);
exit;