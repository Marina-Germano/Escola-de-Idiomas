<?php
session_start();
require_once "../model/Idioma.php";

$idioma = new Idioma();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor']);
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
                echo "Acesso negado. Apenas usuários autorizados podem cadastrar idiomas.";
                exit;
            }
            $idioma->cadastrar($_POST['idioma']);
            echo "Idioma cadastrado com sucesso!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem alterar idiomas.";
                exit;
            }
            $idioma->alterar($_POST['ididioma'], $_POST['idioma']);
            echo "Idioma alterado com sucesso!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem excluir idiomas.";
                exit;
            }
            $idioma->excluir($_GET['ididioma']);
            echo "Idioma excluído com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($idioma->listarTodos());
            break;

        case 'listarId':
            echo json_encode($idioma->listarId($_GET['ididioma']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
