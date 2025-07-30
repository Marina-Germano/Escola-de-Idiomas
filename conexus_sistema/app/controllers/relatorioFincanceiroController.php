<?php
session_start();

// Conexão usando MySQLi procedural
require_once __DIR__ . '/../config/config_relatorios.php';
require_once __DIR__ . '/../libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Monta o HTML
$html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro</title>
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #333;
        margin: 20px;
    }

    h1 {
        color: #4A4A4A;
        font-size: 24px;
        margin: 0;
        text-align: left;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    thead th {
        background-color: #f38b40f5;
        color: white;
        font-weight: bold;
        border: 1px solid #ccc;
        padding: 10px;
        text-align: left;
    }

    td {
        border: 1px solid #ccc;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e6f7ff;
    }

    tfoot td {
        background-color: #f38b40f5;
        color: white;
        font-weight: bold;
        border: 1px solid #ccc;
        padding: 10px;
    }

    p {
        text-align: center;
        font-style: italic;
        color: #666;
    }

    .top-header {
        margin-bottom: 20px;
    }
</style>
</head>
<body>
    <div class="top-header">
        <h1>Relatório de Financeiro</h1>
    </div>';

$sql = "SELECT * FROM pagamento";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $total_pago = 0;
    $total_multa = 0;

    $html .= '<table>
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Valor</th>
                        <th>Data Vencimento</th>
                        <th>Status</th>
                        <th>Data Pagamento</th>
                        <th>Multa</th>
                        <th>Valor total Pago</th>
                    </tr>
                </thead>
                <tbody>';

    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['idaluno']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['valor']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['data_vencimento']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['status_pagamento']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['data_pagamento']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['multa']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['valor_pago']) . '</td>';
        $html .= '</tr>';

        $total_multa += floatval($row['multa']);
        $total_pago += floatval($row['valor_pago']);
    }

    $html .= '</tbody>
                <tfoot>
                <tr>
                    <td colspan="5"></td>
                    <td>Total Multas:</td>
                    <td>' . number_format($total_multa, 2, ',', '.') . '</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td>Total Pago:</td>
                    <td>' . number_format($total_pago, 2, ',', '.') . '</td>
                </tr>
                </tfoot>
            </table>';
} else {
    $html .= '<p>Nenhum dado registrado.</p>';
}

$html .= '</body></html>';

// Fecha a conexão
$conn->close();

// Gera o PDF
$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true);
$dompdf->set_option('defaultFont', 'sans');
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_financeiro.pdf", ["Attachment" => false]);
?>
