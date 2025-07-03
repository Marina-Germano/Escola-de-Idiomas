<?php
session_start();
require_once "../model/DocumentoAluno.php";

$documento = new DocumentoAluno();

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
                echo "Acesso negado. Apenas administradores podem cadastrar documentos.";
                exit;
            }
            $documento->cadastrar(
                $_POST['idtipo_documento'],
                $_POST['nome_arquivo'],
                $_POST['caminho_arquivo'],
                $_POST['observacoes'] ?? null,
                $_POST['status_documento']
            );
            echo "Documento cadastrado com sucesso!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas administradores podem alterar documentos.";
                exit;
            }
            $documento->alterar(
                $_POST['iddocumento_aluno'],
                $_POST['idtipo_documento'],
                $_POST['nome_arquivo'],
                $_POST['caminho_arquivo'],
                $_POST['observacoes'] ?? null,
                $_POST['status_documento']
            );
            echo "Documento alterado com sucesso!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas administradores podem excluir documentos.";
                exit;
            }
            $documento->excluir($_GET['iddocumento_aluno']);
            echo "Documento excluído com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($documento->listarTodos());
            break;

        case 'listarId':
            echo json_encode($documento->listarId($_GET['iddocumento_aluno']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}