<?php
session_start();
require_once "../model/Presenca.php";

$presenca = new Presenca();

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
                echo "Acesso negado. Apenas professores, funcionários ou administradores podem executar essa ação.";
                exit;
            }
            if ($acao == 'cadastrar') {
                $presenca->cadastrar(
                    $_POST['idaula'],
                    $_POST['idaluno'],
                    isset($_POST['presente']) ? (bool)$_POST['presente'] : null,
                    $_POST['observacao'] ?? null
                );
                echo "Presença cadastrada com sucesso!";
            } elseif ($acao == 'alterar') {
                $presenca->alterar(
                    $_POST['idpresenca'],
                    $_POST['idaula'],
                    $_POST['idaluno'],
                    isset($_POST['presente']) ? (bool)$_POST['presente'] : null,
                    $_POST['observacao'] ?? null
                );
                echo "Presença alterada com sucesso!";
            } elseif ($acao == 'excluir') {
                $presenca->excluir($_GET['idpresenca']);
                echo "Presença excluída com sucesso!";
            }
            break;
        case 'listarTodos':
            echo json_encode($presenca->listarTodos());
            break;
        case 'listarId':
            echo json_encode($presenca->listarId($_GET['idpresenca']));
            break;
        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
