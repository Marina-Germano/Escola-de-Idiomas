<?php
// historico_financeiro.php

// Inclua a função que busca o histórico (pode estar num arquivo externo, por exemplo include 'funcoes.php';)
function buscarHistoricoFinanceiroAluno($idAluno) {
    if (empty($idAluno)) {
        return ['error' => 'ID do aluno não informado'];
    }

    $url = "http://localhost/escola-de-idiomas/conexus_sistema/controllers/pagamento_controller.php?acao=listarPorAluno&idaluno=" . urlencode($idAluno);

    $response = @file_get_contents($url);

    if ($response === false) {
        return ['error' => 'Não foi possível conectar ao serviço de pagamentos.'];
    }

    $dados = json_decode($response, true);

    if ($dados === null) {
        return ['error' => 'Resposta inválida do serviço de pagamentos.'];
    }

    return $dados;
}

// Suponha que você pegue o idAluno da sessão ou parâmetro
$idAluno = $_SESSION['idusuario'] ?? null;

// Busca o histórico
$historico = buscarHistoricoFinanceiroAluno($idAluno);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico Financeiro</title>
</head>
<body>

<h1>Histórico Financeiro</h1>

<?php if (isset($historico['error'])): ?>
    <p style="color:red;">Erro: <?= htmlspecialchars($historico['error']) ?></p>
<?php elseif (empty($historico)): ?>
    <p>Nenhum histórico de pagamentos encontrado.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Valor</th>
                <th>Data Vencimento</th>
                <th>Status</th>
                <!-- Outros campos que quiser -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historico as $pagamento): ?>
                <tr>
                    <td>R$ <?= htmlspecialchars($pagamento['valor']) ?></td>
                    <td><?= htmlspecialchars($pagamento['data_vencimento']) ?></td>
                    <td><?= htmlspecialchars($pagamento['status_pagamento']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>
