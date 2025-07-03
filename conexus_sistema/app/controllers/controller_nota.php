<?php
session_start();
require_once "../model/Nota.php";

$nota = new Nota();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['professor', 'funcionario', 'admin']);
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
        case 'alterar':
        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem executar essa ação.";
                exit;
            }
            if ($acao == 'cadastrar') {
                $nota->cadastrar(
                    $_POST['idaluno'],
                    $_POST['idturma'],
                    $_POST['nota']
                );
                echo "Nota cadastrada com sucesso!";
            } elseif ($acao == 'alterar') {
                $nota->alterar(
                    $_POST['idnota'],
                    $_POST['idaluno'],
                    $_POST['idturma'],
                    $_POST['nota']
                );
                echo "Nota alterada com sucesso!";
            } elseif ($acao == 'excluir') {
                $nota->excluir($_GET['idnota']);
                echo "Nota excluída com sucesso!";
            }
            break;
        case 'listarTodos':
            echo json_encode($nota->listarTodos());
            break;
        case 'listarId':
            echo json_encode($nota->listarId($_GET['idnota']));
            break;
        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
