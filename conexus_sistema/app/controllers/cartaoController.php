<?php
session_start();
require_once "../model/Cartao.php";

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

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    switch ($acao) {
        case 'cadastrar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem cadastrar cartões.";
                exit;
            }
            $cartao->cadastrar(
                $_POST['idaluno'],
                $_POST['nome_titular'],
                $_POST['bandeira'],
                $_POST['ultimos_digitos'],
                $_POST['numero_cartao'], // recebe o número do cartão em texto, será criptografado no model
                $_POST['validade_mes'],
                $_POST['validade_ano']
            );
            echo "Cartão cadastrado com sucesso!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem alterar cartões.";
                exit;
            }
            $cartao->alterar(
                $_POST['idcartao'],
                $_POST['idaluno'],
                $_POST['nome_titular'],
                $_POST['bandeira'],
                $_POST['ultimos_digitos'],
                $_POST['numero_cartao'], // também será criptografado no model
                $_POST['validade_mes'],
                $_POST['validade_ano']
            );
            echo "Cartão alterado com sucesso!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem excluir cartões.";
                exit;
            }
            $cartao->excluir($_GET['idcartao']);
            echo "Cartão excluído com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($cartao->listarTodos());
            break;

        case 'listarId':
            echo json_encode($cartao->listarId($_GET['idcartao']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
