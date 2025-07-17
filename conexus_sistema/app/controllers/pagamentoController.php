<?php
session_start();
require_once "../model/Pagamento.php";

$pagamento = new Pagamento();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario']);
}

if (!estaLogado()) {
    http_response_code(401);
    echo "Acesso negado. Faça login para continuar.";
    exit;
}

if (!isset($_GET['acao'])) {
    echo "Nenhuma ação definida.";
    exit;
}

$acao = $_GET['acao'];

switch ($acao) {
    case 'cadastrar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem cadastrar pagamentos.";
            exit;
        }

        $dados = $_POST;

        $ok = $pagamento->cadastrar(
            $dados['idforma_pagamento'],
            $dados['idaluno'],
            $dados['valor'],
            $dados['data_vencimento'],
            $dados['status_pagamento'],
            $dados['data_pagamento'] ?? null,
            $dados['valor_pago'] ?? null,
            $dados['observacoes'] ?? null,
            $dados['multa'] ?? 0.00
        );

        echo $ok ? "Pagamento cadastrado com sucesso!" : "Erro ao cadastrar pagamento.";
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem alterar pagamentos.";
            exit;
        }

        $dados = $_POST;

        $ok = $pagamento->alterar(
            $dados['idpagamento'],
            $dados['idforma_pagamento'],
            $dados['idaluno'],
            $dados['valor'],
            $dados['data_vencimento'],
            $dados['status_pagamento'],
            $dados['data_pagamento'] ?? null,
            $dados['valor_pago'] ?? null,
            $dados['observacoes'] ?? null,
            $dados['multa'] ?? 0.00
        );

        echo $ok ? "Pagamento alterado com sucesso!" : "Erro ao alterar pagamento.";
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir pagamentos.";
            exit;
        }

        $id = $_GET['idpagamento'] ?? null;

        if (!$id) {
            echo "ID do pagamento não informado.";
            exit;
        }

        $ok = $pagamento->excluir($id);
        echo $ok ? "Pagamento excluído com sucesso!" : "Erro ao excluir pagamento.";
        break;

    case 'listarTodos':
        echo json_encode($pagamento->listarTodos());
        break;

    case 'listarPorAluno':
        $idaluno = $_GET['idaluno'] ?? null;
        if (!$idaluno) {
            echo json_encode(['error' => 'ID do aluno não informado']);
            exit;
        }
        echo json_encode($pagamento->listarPorAluno($idaluno));
        break;


    case 'listarId':
        $id = $_GET['idpagamento'] ?? null;

        if (!$id) {
            echo "ID do pagamento não informado.";
            exit;
        }

        echo json_encode($pagamento->listarId($id));
        break;

    default:
        echo "Ação inválida.";
        break;
}
