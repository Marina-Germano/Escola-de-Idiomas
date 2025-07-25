<?php
require_once __DIR__ . '/../config/config_relatorios.php';
require_once __DIR__ . '/../libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    die("ID do aluno não fornecido.");
}

$idAluno = intval($_GET['id']);

// Consulta combinando dados de aluno e usuario
$sql = "SELECT 
        u.nome,
        u.cpf,
        u.data_nascimento,
        u.email,
        u.telefone,
        a.rua,
        a.numero,
        a.bairro,
        a.cep,
        a.complemento,
        a.responsavel,
        a.tel_responsavel
    FROM aluno a
    INNER JOIN usuario u ON a.idusuario = u.idusuario
    WHERE a.idaluno = $idAluno
";

$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Aluno não encontrado.");
}

$aluno = $result->fetch_assoc();
$conn->close();

// HTML do contrato
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contrato de Matrícula</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 40px;
            line-height: 1.6;
        }
        h1 {
            color: #4A4A4A;
            text-align: center;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            color: #f38b40f5;
        }
        .dados {
            margin-bottom: 10px;
        }
        .assinatura {
            margin-top: 60px;
            text-align: center;
        }
        .assinatura div {
            display: inline-block;
            width: 45%;
            margin: 0 2.5%;
        }
        .assinatura p {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <h1>Contrato de Prestação de Serviços Educacionais</h1>

    <p class="section-title">1. Dados do Aluno</p>
    <div class="dados"><strong>Nome:</strong> ' . htmlspecialchars($aluno['nome']) . '</div>
    <div class="dados"><strong>CPF:</strong> ' . htmlspecialchars($aluno['cpf']) . '</div>
    <div class="dados"><strong>Data de Nascimento:</strong> ' . htmlspecialchars(date('d/m/Y', strtotime($aluno['data_nascimento']))) . '</div>
    <div class="dados"><strong>Email:</strong> ' . htmlspecialchars($aluno['email']) . '</div>
    <div class="dados"><strong>Telefone:</strong> ' . htmlspecialchars($aluno['telefone']) . '</div>

    <p class="section-title">2. Endereço</p>
    <div class="dados"><strong>Rua:</strong> ' . htmlspecialchars($aluno['rua']) . ', ' . htmlspecialchars($aluno['numero']) . '</div>
    <div class="dados"><strong>Bairro:</strong> ' . htmlspecialchars($aluno['bairro']) . '</div>
    <div class="dados"><strong>CEP:</strong> ' . htmlspecialchars($aluno['cep']) . '</div>
    <div class="dados"><strong>Complemento:</strong> ' . htmlspecialchars($aluno['complemento'] ?? '-') . '</div>

    <p class="section-title">3. Responsável</p>
    <div class="dados"><strong>Nome:</strong> ' . htmlspecialchars($aluno['responsavel']) . '</div>
    <div class="dados"><strong>Telefone:</strong> ' . htmlspecialchars($aluno['tel_responsavel']) . '</div>

    <p class="section-title">4. Termos</p>
    <p>Este contrato tem por finalidade a prestação de serviços educacionais pela instituição Conexus ao aluno acima identificado, conforme regulamento interno. Ao assinar este contrato, o responsável legal concorda com os termos descritos.</p>

    <div class="assinatura">
        <div>
            <p>Responsável</p>
        </div>
        <div>
            <p>Instituição</p>
        </div>
    </div>

</body>
</html>
';

// Gerar o PDF com Dompdf
$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("contrato_aluno_" . preg_replace('/\s+/', '_', $aluno['nome']) . ".pdf", ["Attachment" => false]);
