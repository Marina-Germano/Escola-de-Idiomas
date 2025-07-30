<?php
session_start();
require_once "../models/cartao.php";

$cartao = new Cartao();

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

if (!isset($_GET['acao'])) {
    echo "Nenhuma ação definida.";
    exit;
}

$acao = $_GET['acao'];

switch ($acao) {
    case 'cadastrar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem cadastrar cartões.";
            exit;
        }

        if (!isset($_POST['idaluno'], $_POST['nome_titular'], $_POST['bandeira'], $_POST['ultimos_digitos'], $_POST['numero_cartao'], $_POST['validade_mes'], $_POST['validade_ano'])) {
            http_response_code(400);
            echo "Campos obrigatórios não informados.";
            exit;
        }

        $ok = $cartao->cadastrar(
            $_POST['idaluno'],
            $_POST['nome_titular'],
            $_POST['bandeira'],
            $_POST['ultimos_digitos'],
            $_POST['numero_cartao'],
            $_POST['validade_mes'],
            $_POST['validade_ano']
        );

        header("Location: ../views/components/sucess.php?cadastrar=ok");
            exit;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem alterar cartões.";
            exit;
        }

        if (!isset($_POST['idcartao'], $_POST['idaluno'], $_POST['nome_titular'], $_POST['bandeira'], $_POST['ultimos_digitos'], $_POST['numero_cartao'], $_POST['validade_mes'], $_POST['validade_ano'])) {
            http_response_code(400);
            echo "Campos obrigatórios não informados.";
            exit;
        }

        $ok = $cartao->alterar(
            $_POST['idcartao'],
            $_POST['idaluno'],
            $_POST['nome_titular'],
            $_POST['bandeira'],
            $_POST['ultimos_digitos'],
            $_POST['numero_cartao'],
            $_POST['validade_mes'],
            $_POST['validade_ano']
        );

        header("Location: ../views/components/sucess.php?alterar=ok");
            exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem excluir cartões.";
            exit;
        }

        if (!isset($_GET['idcartao'])) {
            http_response_code(400);
            echo "ID do cartão não informado.";
            exit;
        }

        $ok = $cartao->excluir($_GET['idcartao']);

        header("Location: ../views/components/sucess.php?excluir=ok");
            exit;

    case 'listarTodos':
        echo json_encode($cartao->listarTodos());
        break;

    case 'listarId':
        if (!isset($_GET['idcartao'])) {
            http_response_code(400);
            echo "ID do cartão não informado.";
            exit;
        }

        echo json_encode($cartao->listarId($_GET['idcartao']));
        break;

    default:
        echo "Ação inválida.";
        break;
}
