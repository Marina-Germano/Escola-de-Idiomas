<?php
session_start();
require_once "../model/FormaPagamento.php";

$formaPagamento = new FormaPagamento();

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

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    switch ($acao) {
        case 'cadastrar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem cadastrar formas de pagamento.";
                exit;
            }
            $formaPagamento->cadastrar($_POST['forma_pagamento']);
            echo "Forma de pagamento cadastrada com sucesso!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem alterar formas de pagamento.";
                exit;
            }
            $formaPagamento->alterar($_POST['idforma_pagamento'], $_POST['forma_pagamento']);
            echo "Forma de pagamento alterada com sucesso!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem excluir formas de pagamento.";
                exit;
            }
            $formaPagamento->excluir($_GET['idforma_pagamento']);
            echo "Forma de pagamento excluída com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($formaPagamento->listarTodos());
            break;

        case 'listarId':
            echo json_encode($formaPagamento->listarId($_GET['idforma_pagamento']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
