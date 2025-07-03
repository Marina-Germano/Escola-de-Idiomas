<?php
session_start();
require_once "../model/Contato.php";

$contato = new Contato();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

// Aqui pode ajustar permissões conforme desejar. Exemplo: qualquer usuário logado pode criar contato, mas só admins podem alterar/excluir
function temPermissaoAlterarExcluir() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario']); //verificar quem vai ficar aqui
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
            // Permite qualquer usuário logado criar contato
            $contato->cadastrar(
                $_POST['idusuario'],
                $_POST['nome'],
                $_POST['email'],
                $_POST['motivo_contato'] ?? null,
                $_POST['observacoes'] ?? null
            );
            echo "Contato cadastrado com sucesso!";
            break;

        case 'alterar':
        case 'excluir':
            if (!temPermissaoAlterarExcluir()) {
                http_response_code(403);
                echo "Acesso negado. Apenas administradores e funcionários podem executar essa ação.";
                exit;
            }
            if ($acao == 'alterar') {
                $contato->alterar(
                    $_POST['idusuario'],
                    $_POST['nome'],
                    $_POST['email'],
                    $_POST['motivo_contato'] ?? null,
                    $_POST['observacoes'] ?? null
                );
                echo "Contato alterado com sucesso!";
            } elseif ($acao == 'excluir') {
                $contato->excluir($_GET['idusuario']);
                echo "Contato excluído com sucesso!";
            }
            break;

        case 'listarTodos':
            echo json_encode($contato->listarTodos());
            break;

        case 'listarId':
            echo json_encode($contato->listarId($_GET['idusuario']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
