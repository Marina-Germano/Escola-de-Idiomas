<?php
session_start();
require_once "../model/AlunoTurma.php";

$alunoTurma = new AlunoTurma();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

// Ajuste as permissões conforme sua regra de negócio
function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['funcionario', 'admin']);
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    switch ($acao) {
        case 'cadastrar':
            if (!estaLogado() || !temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem cadastrar matrículas.";
                exit;
            }
            $alunoTurma->cadastrar(
                $_POST['idaluno'],
                $_POST['idturma'],
                $_POST['data_matricula']
            );
            echo "Matrícula cadastrada com sucesso!";
            break;

        case 'alterar':
            if (!estaLogado() || !temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem alterar matrículas.";
                exit;
            }
            $alunoTurma->alterar(
                $_POST['idaluno_turma'],
                $_POST['idaluno'],
                $_POST['idturma'],
                $_POST['data_matricula']
            );
            echo "Matrícula alterada com sucesso!";
            break;

        case 'excluir':
            if (!estaLogado() || !temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. ccc";
                exit;
            }
            $alunoTurma->excluir($_GET['idaluno_turma']);
            echo "Matrícula excluída com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($alunoTurma->listarTodos());
            break;

        case 'listarId':
            echo json_encode($alunoTurma->listarId($_GET['idaluno_turma']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
