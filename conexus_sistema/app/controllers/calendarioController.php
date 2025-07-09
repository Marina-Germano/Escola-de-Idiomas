<?php
session_start();
require_once "../model/CalendarioAula.php";

$calendario = new CalendarioAula();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario','professor']);
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
            echo "Apenas usuários autorizados podem cadastrar aulas.";
            exit;
        }

        $ok = $calendario->cadastrar(
            $_POST['data_aula'],
            $_POST['hora_inicio'],
            $_POST['hora_fim'],
            $_POST['idprofessor'],
            $_POST['idturma'],
            $_POST['idmaterial'],
            $_POST['sala'] ?? null,
            $_POST['observacoes'] ?? null,
            $_POST['link_reuniao'] ?? null,
            isset($_POST['aula_extra']) ? (bool)$_POST['aula_extra'] : false
        );

        echo $ok ? "Aula cadastrada com sucesso!" : "Erro ao cadastrar aula.";
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar aulas.";
            exit;
        }

        $ok = $calendario->alterar(
            $_POST['idaula'],
            $_POST['data_aula'],
            $_POST['hora_inicio'],
            $_POST['hora_fim'],
            $_POST['idprofessor'],
            $_POST['idturma'],
            $_POST['idmaterial'],
            $_POST['sala'] ?? null,
            $_POST['observacoes'] ?? null,
            $_POST['link_reuniao'] ?? null,
            isset($_POST['aula_extra']) ? (bool)$_POST['aula_extra'] : false
        );

        echo $ok ? "Aula alterada com sucesso!" : "Erro ao alterar aula.";
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir aulas.";
            exit;
        }

        if (!isset($_GET['idaula'])) {
            http_response_code(400);
            echo "ID da aula não informado.";
            exit;
        }

        $ok = $calendario->excluir($_GET['idaula']);
        echo $ok ? "Aula excluída com sucesso!" : "Erro ao excluir aula.";
        break;

    case 'listarTodos':
        echo json_encode($calendario->listarTodos());
        break;

    case 'listarId':
        if (!isset($_GET['idaula'])) {
            http_response_code(400);
            echo "ID da aula não informado.";
            exit;
        }

        echo json_encode($calendario->listarId($_GET['idaula']));
        break;

    default:
        echo "Ação inválida.";
        break;
}
