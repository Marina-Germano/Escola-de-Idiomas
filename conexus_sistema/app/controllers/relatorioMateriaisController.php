<?php
session_start();

require_once __DIR__ . '/../config/config_relatorios.php'; // ajuste conforme a sua estrutura
require_once __DIR__ . '/../libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Verifica conexão PDO
if (!$conn) {
    die("Erro na conexão com o banco.");
}

// --- Removido código que obtém caminho da imagem ---

// Consulta SQL materiais
$sql = "SELECT * FROM material";
$result = $conn->query($sql);

$materiais = [];
if ($result) {
    while ($row = $result->fetch_object()) {
        $materiais[] = $row;
    }
} // array de objetos

// Monta o HTML com estilo parecido ao financeiro
$html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Materiais</title>
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
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        position: relative;
    }
    /* removida a classe .top-header img pois não usaremos imagem */
    .top-header h1 {
        /* removi margem lateral pois não tem mais imagem */
        margin-left: 0;
    }
</style>
</head>
<body>
    <div class="top-header">';
    // REMOVIDO o trecho que adiciona a imagem
    $html .= '<h1>Relatório de Materiais</h1>
    </div>';

if (count($materiais) > 0) {
    $html .= '<table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Quantidade</th>
                        <th>Formato do Arquivo</th>
                    </tr>
                </thead>
                <tbody>';
    foreach ($materiais as $mat) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($mat->titulo) . '</td>';
        $html .= '<td>' . htmlspecialchars($mat->quantidade) . '</td>';
        $html .= '<td>' . htmlspecialchars($mat->formato_arquivo) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p>Nenhum dado registrado.</p>';
}

$html .= '</body></html>';

// Fecha conexão PDO
$conn = null;

// Gera o PDF
$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true); // para permitir imagens locais, pode deixar ou tirar
$dompdf->set_option('defaultFont', 'sans');
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_materiais.pdf", ["Attachment" => false]);
?>