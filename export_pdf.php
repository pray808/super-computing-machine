<?php

include 'includes/db.php';
require_once 'includes/fpdf/fpdf.php';

$total_trafic = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM trafic"
    )
)['total'];

$total_infractions = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM infractions"
    )
)['total'];

$total_urgences = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM urgence"
    )
)['total'];

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(
    190,
    10,
    'BUNIA TRAFFIC MANAGEMENT SYSTEM',
    0,
    1,
    'C'
);

$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);

$pdf->Cell(
    190,
    10,
    'Rapport de Supervision Routiere',
    0,
    1,
    'C'
);

$pdf->Ln(10);

$pdf->Cell(
    95,
    10,
    'Ville : Bunia',
    0,
    0
);

$pdf->Cell(
    95,
    10,
    'Date : ' . date('d/m/Y'),
    0,
    1
);

$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(
    190,
    10,
    'Statistiques Generales',
    1,
    1,
    'C'
);

$pdf->SetFont('Arial', '', 12);

$pdf->Cell(
    120,
    10,
    'Total Trafic',
    1,
    0
);

$pdf->Cell(
    70,
    10,
    $total_trafic,
    1,
    1
);

$pdf->Cell(
    120,
    10,
    'Total Infractions',
    1,
    0
);

$pdf->Cell(
    70,
    10,
    $total_infractions,
    1,
    1
);

$pdf->Cell(
    120,
    10,
    'Total Urgences',
    1,
    0
);

$pdf->Cell(
    70,
    10,
    $total_urgences,
    1,
    1
);

$pdf->Ln(10);

$pdf->SetFont('Arial', 'I', 10);

$pdf->MultiCell(
    190,
    7,
    'Ce rapport a ete genere automatiquement par le systeme Pray808 pour assister les autorites de la Ville de Bunia dans la supervision du trafic routier.'
);

$pdf->Output(
    'I',
    'rapport_btms.pdf'
);
