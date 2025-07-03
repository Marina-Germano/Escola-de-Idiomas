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
    echo "Acesso negado. Faça login.";
    exit;
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    switch ($acao) {
        case 'cadastrar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Somente funcionários e administradores podem cadastrar pagamentos.";
                exit;
            }
            $pagamento->cadastrar(
                $_POST['valor'],
                $_POST['data_vencimento'],
                $_POST['status_pagamento'],
                $_POST['data_pagamento'],
                $_POST['valor_pago'],
                $_POST['observacoes'],
                $_POST['multa']
            );
            echo "Pagamento cadastrado!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Sem permissão para alterar pagamento.";
                exit;
            }
            $pagamento->alterar(
                $_POST['idaluno'],
                $_POST['valor'],
                $_POST['data_vencimento'],
                $_POST['status_pagamento'],
                $_POST['data_pagamento'],
                $_POST['valor_pago'],
                $_POST['observacoes'],
                $_POST['multa']
            );
            echo "Pagamento alterado!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Sem permissão para excluir pagamento.";
                exit;
            }
            $pagamento->excluir($_GET['idpagamento']);
            echo "Pagamento excluído!";
            break;

        case 'listarTodos':
            echo json_encode($pagamento->listarTodos());
            break;

        case 'listarId':
            echo json_encode($pagamento->listarId($_GET['idpagamento']));
            break;

        default:
            echo "Ação inválida.";
            break;

            //tudo que entrou, tudo o que saiu, tudo o que foi pago, tudo o que está pendente
    }
} else {
    echo "Nenhuma ação definida.";
}
