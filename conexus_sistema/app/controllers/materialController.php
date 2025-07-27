<?php
session_start();
require_once "../models/material.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$material = new Material();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor', 'aluno']);
}

function redirecionarUsuario() {
    $papel = $_SESSION['papel'];
    if ($papel === 'admin') {
        header("Location: ../views/admin/list_material.php");
    } elseif ($papel === 'professor') {
        header("Location: ../views/teacher/list_material.php");
    } else {
        header("Location: ../views/list_material.php");
    }
    exit;
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
            echo "Apenas usuários autorizados podem cadastrar materiais.";
            exit;
        }

        if (!isset($_POST['idturma']) || empty($_POST['idturma'])) {
            $_POST['idturma'] = null;
        }

        $ok = $material->cadastrar([
            'idtipo_material'   => $_POST['idtipo_material'],
            'ididioma'          => $_POST['ididioma'],
            'idnivel'           => $_POST['idnivel'],
            'idturma'           => $_POST['idturma'],
            'titulo'            => $_POST['titulo'],
            'descricao'         => $_POST['descricao'] ?? "",
            'quantidade'        => $_POST['quantidade'],
            'formato_arquivo'   => $_POST['formato_arquivo'] ?? "",
            'arquivo'           => $_POST['arquivo'] ?? ""
        ]);
        redirecionarUsuario();
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar materiais.";
            exit;
        }

        $ok = $material->alterar($_POST['idmaterial'], [
            'idtipo_material'   => $_POST['idtipo_material'],
            'ididioma'          => $_POST['ididioma'],
            'idnivel'           => $_POST['idnivel'],
            'idturma'           => $_POST['idturma'],
            'titulo'            => $_POST['titulo'],
            'descricao'         => $_POST['descricao'],
            'quantidade'        => $_POST['quantidade'],
            'formato_arquivo'   => $_POST['formato_arquivo'],
            'arquivo'           => $_POST['arquivo'],
        ]);
        redirecionarUsuario();
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir materiais.";
            exit;
        }
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "ID do material não informado.";
            exit;
        }
        $ok = $material->excluir($_GET['id']);
        redirecionarUsuario();
        break;

    case 'listar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        echo json_encode($material->listar());
        break;

    case 'buscarPorId':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "ID do material não informado.";
            exit;
        }
        echo json_encode($material->buscarPorId($_GET['id']));
        break;

    case 'listarPorAluno':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['idaluno'])) {
            http_response_code(400);
            echo "ID do aluno não informado.";
            exit;
        }
        echo json_encode($material->listarMateriaisPorAluno($_GET['idaluno']));
        break;

    case 'ultimosPorAluno':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['idaluno'])) {
            http_response_code(400);
            echo "ID do aluno não informado.";
            exit;
        }
        $limite = $_GET['limite'] ?? 6;
        echo json_encode($material->getUltimosMateriaisPorAluno($_GET['idaluno'], $limite));
        break;

    case 'listarPorTurma':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['idturma'])) {
            http_response_code(400);
            echo "ID da turma não informado.";
            exit;
        }
        echo json_encode($material->listarPorTurma($_GET['idturma']));
        break;

    default:
        echo "Ação inválida.";
        break;
}
