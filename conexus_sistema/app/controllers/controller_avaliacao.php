<?php
session_start();
require_once "../model/Avaliacao.php";

$avaliacao = new Avaliacao();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

// Ajuste as permissões conforme sua regra de negócio
function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'professor']);
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    switch ($acao) {
        case 'cadastrar':
            if (!estaLogado() || !temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem cadastrar avaliações.";
                exit;
            }
            $avaliacao->cadastrar(
                $_POST['idaluno_turma'],
                $_POST['tipo_avaliacao'],
                $_POST['titulo'] ?? null,
                $_POST['data_avaliacao'],
                $_POST['nota'],
                $_POST['peso'] ?? 1.0,
                $_POST['observacao'] ?? null
            );
            echo "Avaliação cadastrada com sucesso!";
            break;

        case 'alterar':
            if (!estaLogado() || !temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem alterar avaliações.";
                exit;
            }
            $avaliacao->alterar(
                $_POST['idavaliacao'],
                $_POST['idaluno_turma'],
                $_POST['tipo_avaliacao'],
                $_POST['titulo'] ?? null,
                $_POST['data_avaliacao'],
                $_POST['nota'],
                $_POST['peso'] ?? 1.0,
                $_POST['observacao'] ?? null
            );
            echo "Avaliação alterada com sucesso!";
            break;

        case 'excluir':
            if (!estaLogado() || !temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem excluir avaliações.";
                exit;
            }
            $avaliacao->excluir($_GET['idavaliacao']);
            echo "Avaliação excluída com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($avaliacao->listarTodos());
            break;

        case 'listarId':
            echo json_encode($avaliacao->listarId($_GET['idavaliacao']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
