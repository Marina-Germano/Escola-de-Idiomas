<?php
session_start();
require_once "../model/Professor.php";

$professor = new Professor();

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
                echo "Apenas administradores ou funcionários podem cadastrar professores.";
                exit;
            }
            $professor->cadastrar(
                $_POST['nome'],
                $_POST['cpf'],
                $_POST['datanascimento'],
                $_POST['email'],
                $_POST['telefone'],
                $_POST['especialidade']
            );
            echo "Professor cadastrado com sucesso!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Apenas administradores ou funcionários podem alterar professores.";
                exit;
            }
            $professor->alterar(
                $_POST['idprofessor'],
                $_POST['nome'],
                $_POST['cpf'],
                $_POST['datanascimento'],
                $_POST['email'],
                $_POST['telefone'],
                $_POST['especialidade']
            );
            echo "Professor alterado com sucesso!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Apenas administradores ou funcionários podem excluir professores.";
                exit;
            }
            $professor->excluir($_GET['idprofessor']);
            echo "Professor excluído com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($professor->listarTodos());
            break;

        case 'listarId':
            echo json_encode($professor->listarId($_GET['idprofessor']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
