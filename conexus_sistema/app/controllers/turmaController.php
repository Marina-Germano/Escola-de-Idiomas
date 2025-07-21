<?php
session_start();
require_once "../models/turma.php";

$turma = new Turma();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario']);
}

// Roteamento da ação
$acao = $_GET['acao'] ?? $_POST['acao'] ?? '';

switch ($acao) {
    case 'cadastrar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }

        $imagem = $_FILES['imagem']['name'] ?? null;
        $caminhoImagem = null;
        if ($imagem) {
            $caminhoImagem = "../uploads/" . basename($imagem);
            move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem);
        }

        $sucesso = $turma->cadastrar(
            $_POST['ididioma'],
            $_POST['idnivel'],
            $_POST['descricao'],
            $_POST['dias_semana'],
            $_POST['hora_inicio'],
            $_POST['capacidade_maxima'],
            $_POST['sala'],
            $caminhoImagem,
            $_POST['idprofessor'],
            $_POST['tipo_recorrencia'] ?? null
        );

        header("Location: ../views/turma/sucesso.php");
        exit;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }

        $imagem = $_FILES['imagem']['name'] ?? null;
        $caminhoImagem = $_POST['imagem_atual'] ?? null;
        if ($imagem) {
            $caminhoImagem = "../uploads/" . basename($imagem);
            move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem);
        }

        $turma->alterar(
            $_POST['idturma'],
            $_POST['ididioma'],
            $_POST['idnivel'],
            $_POST['descricao'],
            $_POST['dias_semana'],
            $_POST['hora_inicio'],
            $_POST['capacidade_maxima'],
            $_POST['sala'],
            $caminhoImagem,
            $_POST['idprofessor'],
            $_POST['tipo_recorrencia'] ?? null
        );

        header("Location: ../views/turma/sucesso.php");
        exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }

        $turma->excluir($_GET['id']);
        header("Location: ../views/turma/listar.php");
        exit;

    case 'listarTodos':
        echo json_encode($turma->listarTodos());
        break;

    case 'listarId':
        echo json_encode($turma->listarId($_GET['id']));
        break;

    default:
        echo "Ação inválida.";
}
