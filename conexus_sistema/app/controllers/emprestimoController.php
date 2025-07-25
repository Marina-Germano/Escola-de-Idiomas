<?php
session_start();
require_once "../models/emprestimo_material.php";

$emprestimoMaterial = new EmprestimoMaterial();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    // Permite admin e funcionario gerenciar empréstimos
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
            echo "Apenas usuários autorizados podem cadastrar empréstimos.";
            exit;
        }

        $ok = $emprestimoMaterial->cadastrar(
            $_POST['idaluno'],
            $_POST['idmaterial'],
            $_POST['data_emprestimo'],
            $_POST['data_prevista_devolucao'],
            $_POST['data_devolvido'] ?? null,
            $_POST['status'] ?? 'emprestado',
            $_POST['observacoes'] ?? null,
            $_POST['valor_multa'] ?? 0.00
        );

        header("Location: ../views/admin/material_loan.php");
            exit;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar empréstimos.";
            exit;
        }

        $ok = $emprestimoMaterial->alterar(
            $_POST['idemprestimo'],
            $_POST['idaluno'],
            $_POST['idmaterial'],
            $_POST['data_emprestimo'],
            $_POST['data_prevista_devolucao'],
            $_POST['data_devolvido'] ?? null,
            $_POST['status'] ?? 'emprestado',
            $_POST['observacoes'] ?? null,
            $_POST['valor_multa'] ?? 0.00
        );

        header("Location: ../views/admin/material_loan.php");
            exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir empréstimos.";
            exit;
        }
        if (!isset($_GET['idemprestimo'])) {
            http_response_code(400);
            echo "ID do empréstimo não informado.";
            exit;
        }
        $ok = $emprestimoMaterial->excluir($_GET['idemprestimo']);
        header("Location: ../views/admin/dashboard");
            exit;

    case 'listarTodos':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        echo json_encode($emprestimoMaterial->listarTodos());
        break;

    case 'listarId':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['idemprestimo'])) {
            http_response_code(400);
            echo "ID do empréstimo não informado.";
            exit;
        }
        echo json_encode($emprestimoMaterial->listarId($_GET['idemprestimo']));
        break;

    default:
        echo "Ação inválida.";
        break;
}
