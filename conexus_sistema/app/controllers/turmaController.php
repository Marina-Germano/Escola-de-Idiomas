<?php
session_start();
require_once "../models/turma.php";
require_once "../models/funcionario.php";

$turma = new Turma();
$funcionario = new Funcionario();


function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor']);
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
            $caminhoImagem = "../uploads/" . $imagem;
            move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem);
        }

        $idfuncionario = $_POST['idfuncionario'];
        $dadosFuncionario = $funcionario->listarId($idfuncionario);

        if (!$dadosFuncionario) {
            echo "Funcionário não encontrado.";
            exit;
        }

        if (strtolower($dadosFuncionario['cargo']) !== 'professor') {
            echo "Apenas funcionários com cargo de professor podem ser responsáveis por turmas.";
            exit;
        }

        $diasSemana = is_array($_POST['dias_semana'])
    ? implode(', ', $_POST['dias_semana'])
    : $_POST['dias_semana'];


        $sucess = $turma->cadastrar(
            $_POST['ididioma'],
            $_POST['idnivel'],
            $_POST['descricao'],
            $diasSemana,
            $_POST['hora_inicio'],
            $_POST['capacidade_maxima'],
            $_POST['sala'],
            $caminhoImagem,
            $_POST['idfuncionario'],
            $_POST['tipo_recorrencia'] ?? null
        );

        header("Location: ../views/admin/list_class.php");
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
            $caminhoImagem = "../uploads/" . $imagem;
            move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem);
        }
        $diasSemana = is_array($_POST['dias_semana'])
    ? implode(', ', $_POST['dias_semana'])
    : $_POST['dias_semana'];

        $turma->alterar(
            $_POST['idturma'],
            $_POST['ididioma'],
            $_POST['idnivel'],
            $_POST['descricao'],
            $diasSemana,
            $_POST['hora_inicio'],
            $_POST['capacidade_maxima'],
            $_POST['sala'],
            $caminhoImagem,
            $_POST['idfuncionario'],
            $_POST['tipo_recorrencia'] ?? null
        );

        header("Location: ../views/admin/list_class.php");
        exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }

        $turma->excluir($_GET['id']);
        header("Location: ../views/admin/list_class.php");
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
