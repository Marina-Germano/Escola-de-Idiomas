<?php
session_start();
require_once "../model/Nivel.php";

$nivel = new Nivel();

// Verifica se o usuário está logado
function estaLogado() {
    return isset($_SESSION['idusuario']);
}

// Verifica se o usuário tem permissão para gerenciar níveis
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
            echo "Apenas usuários autorizados podem cadastrar níveis.";
            exit;
        }

        $descricao = $_POST['descricao'] ?? null;

        if (!$descricao) {
            echo "Erro: descrição é obrigatória.";
            exit;
        }

        $nivel->cadastrar($descricao);
        echo "Nível cadastrado com sucesso!";
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar níveis.";
            exit;
        }

        $idnivel = $_POST['idnivel'] ?? null;
        $descricao = $_POST['descricao'] ?? null;

        if (!$idnivel || !$descricao) {
            echo "Erro: ID e descrição são obrigatórios.";
            exit;
        }

        $nivel->alterar($idnivel, $descricao);
        echo "Nível alterado com sucesso!";
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir níveis.";
            exit;
        }

        $idnivel = $_GET['idnivel'] ?? null;

        if (!$idnivel) {
            echo "Erro: ID do nível não informado.";
            exit;
        }

        $nivel->excluir($idnivel);
        echo "Nível excluído com sucesso!";
        break;

    case 'listarTodos':
        echo json_encode($nivel->listarTodos());
        break;

    case 'listarId':
        $idnivel = $_GET['idnivel'] ?? null;

        if (!$idnivel) {
            echo "Erro: ID do nível não informado.";
            exit;
        }

        echo json_encode($nivel->listarId($idnivel));
        break;

    default:
        echo "Ação inválida.";
        break;
}
