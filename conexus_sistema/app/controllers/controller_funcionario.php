<?php
session_start();
require_once "../model/Funcionario.php";

$funcionario = new Funcionario();

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
                echo "Acesso negado. Apenas usuários autorizados podem cadastrar funcionários.";
                exit;
            }
            $funcionario->cadastrar(
                $_POST['nome'],
                $_POST['cpf'],
                $_POST['datanascimento'],
                $_POST['email'],
                $_POST['telefone'],
                $_POST['cargo'],
                $_POST['turno']
            );
            echo "Funcionário cadastrado com sucesso!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem alterar funcionários.";
                exit;
            }
            $funcionario->alterar(
                $_POST['idfuncionario'],
                $_POST['nome'],
                $_POST['cpf'],
                $_POST['datanascimento'],
                $_POST['email'],
                $_POST['telefone'],
                $_POST['cargo'],
                $_POST['turno']
            );
            echo "Funcionário alterado com sucesso!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem excluir funcionários.";
                exit;
            }
            $funcionario->excluir($_GET['idfuncionario']);
            echo "Funcionário excluído com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($funcionario->listarTodos());
            break;

        case 'listarId':
            echo json_encode($funcionario->listarId($_GET['idfuncionario']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
