<?php
session_start();
require_once "../models/forma_pagamento.php";

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

if (!isset($_GET['acao'])) {
    echo "Nenhuma ação definida.";
    exit;
}

$acao = $_GET['acao'];

switch ($acao) {
    case 'cadastrar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem cadastrar formas de pagamento.";
            exit;
        }

        $descricao = $_POST['forma_pagamento'] ?? null;

        if (!$descricao) {
            echo "Descrição da forma de pagamento é obrigatória.";
            exit;
        }

        $formaPagamento->cadastrar($descricao);
        header("Location: ../views/components/sucess.php?cadastrar=ok");
            exit;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar formas de pagamento.";
            exit;
        }

        $id = $_POST['idforma_pagamento'] ?? null;
        $descricao = $_POST['forma_pagamento'] ?? null;

        if (!$id || !$descricao) {
            echo "ID e descrição são obrigatórios para alteração.";
            exit;
        }

        $formaPagamento->alterar($id, $descricao);
        header("Location: ../views/components/sucess.php?alterar=ok");
            exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir formas de pagamento.";
            exit;
        }

        $id = $_GET['idforma_pagamento'] ?? null;

        if (!$id) {
            echo "ID da forma de pagamento não informado.";
            exit;
        }

        $formaPagamento->excluir($id);
        header("Location: ../views/components/sucess.php?excluir=ok");
        exit;

    case 'listarTodos':
        echo json_encode($formaPagamento->listarTodos());
        break;

    case 'listarId':
        $id = $_GET['idforma_pagamento'] ?? null;

        if (!$id) {
            echo "ID da forma de pagamento não informado.";
            exit;
        }

        echo json_encode($formaPagamento->listarId($id));
        break;

    default:
        echo "Ação inválida.";
        break;
}
