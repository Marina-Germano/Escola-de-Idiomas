<?php
session_start();
require_once "../model/Turma.php";

$turma = new Turma();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissaoTotal() {
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
            if (!temPermissaoTotal()) {
                http_response_code(403);
                echo "Apenas administradores, professores ou funcionários podem cadastrar turmas.";
                exit;
            }

            $turma->cadastrar(
                $_POST['ididioma'],
                $_POST['data_aula'],
                $_POST['hora_aula'],
                $_POST['capacidade_maxima'],
                $_POST['sala'],
                $_POST['idprofessor'],
                $_POST['tipo_recorrencia']
            );
            echo "Turma cadastrada com sucesso!";
            break;

        case 'alterar':
            if (!temPermissaoTotal()) {
                http_response_code(403);
                echo "Apenas administradores, professores ou funcionários podem alterar turmas.";
                exit;
            }

            $turma->alterar(
                $_POST['idturma'],
                $_POST['ididioma'],
                $_POST['data_aula'],
                $_POST['hora_aula'],
                $_POST['capacidade_maxima'],
                $_POST['sala'],
                $_POST['idprofessor'],
                $_POST['tipo_recorrencia']
            );
            echo "Turma alterada com sucesso!";
            break;

        case 'excluir':
            if (!temPermissaoTotal()) {
                http_response_code(403);
                echo "Apenas administradores, professores ou funcionários podem excluir turmas.";
                exit;
            }

            $turma->excluir($_GET['idturma']);
            echo "Turma excluída com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($turma->listarTodos());
            break;

        case 'listarId':
            echo json_encode($turma->listarId($_GET['idturma']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
