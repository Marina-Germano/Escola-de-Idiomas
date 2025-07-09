<?php
session_start();
require_once "../model/Idioma.php";

$idioma = new Idioma();

// Função para verificar se o usuário está logado
function estaLogado() {
    return isset($_SESSION['idusuario']);
}

// Função para verificar se o usuário tem permissão (ex: admin ou funcionário)
function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario']);
}

// Impede acesso não autenticado
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
            echo "Apenas usuários autorizados podem cadastrar idiomas.";
            exit;
        }

        $descricao = $_POST['descricao'] ?? null;

        if (!$descricao) {
            echo "Erro: descrição é obrigatória.";
            exit;
        }

        $idioma->cadastrar($descricao);
        echo "Idioma cadastrado com sucesso!";
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar idiomas.";
            exit;
        }

        $ididioma = $_POST['ididioma'] ?? null;
        $descricao = $_POST['descricao'] ?? null;

        if (!$ididioma || !$descricao) {
            echo "Erro: ID e descrição são obrigatórios.";
            exit;
        }

        $idioma->alterar($ididioma, $descricao);
        echo "Idioma alterado com sucesso!";
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir idiomas.";
            exit;
        }

        $ididioma = $_GET['ididioma'] ?? null;

        if (!$ididioma) {
            echo "Erro: ID do idioma não informado.";
            exit;
        }

        $idioma->excluir($ididioma);
        echo "Idioma excluído com sucesso!";
        break;

    case 'listarTodos':
        echo json_encode($idioma->listarTodos());
        break;

    case 'listarId':
        $ididioma = $_GET['ididioma'] ?? null;

        if (!$ididioma) {
            echo "Erro: ID do idioma não informado.";
            exit;
        }

        echo json_encode($idioma->listarId($ididioma));
        break;

    default:
        echo "Ação inválida.";
        break;
}
