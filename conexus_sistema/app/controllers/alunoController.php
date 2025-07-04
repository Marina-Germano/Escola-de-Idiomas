<?php
session_start();
require_once "../model/Aluno.php";
require_once "../model/material.php";

$aluno = new Aluno();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

// Ajuste permissões conforme seu sistema — ex: apenas admin e funcionário podem alterar dados dos alunos
function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario']); //edito aqui quem tem a permissao
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
                echo "Acesso negado. Apenas usuários autorizados podem cadastrar alunos.";
                exit;
            }
            $aluno->cadastrar(
                $_POST['nome'],
                $_POST['cpf'],
                $_POST['cep'],
                $_POST['rua'],
                $_POST['numero'],
                $_POST['bairro'],
                $_POST['complemento'],
                $_POST['responsavel'],
                $_POST['tel_responsavel'],
                $_POST['datanascimento'],
                $_POST['email'],
                $_POST['telefone'],
                $_POST['situacao']
            );
            echo "Aluno cadastrado com sucesso!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem alterar alunos.";
                exit;
            }
            $aluno->alterar(
                $_POST['idaluno'],
                $_POST['nome'],
                $_POST['cpf'],
                $_POST['cep'],
                $_POST['rua'],
                $_POST['numero'],
                $_POST['bairro'],
                $_POST['complemento'],
                $_POST['responsavel'],
                $_POST['tel_responsavel'],
                $_POST['datanascimento'],
                $_POST['email'],
                $_POST['telefone']
            );
            echo "Aluno alterado com sucesso!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem excluir alunos.";
                exit;
            }
            $aluno->excluir($_GET['idaluno']);
            echo "Aluno excluído com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($aluno->listarTodos());
            break;

        case 'listarId':
            echo json_encode($aluno->listarId($_GET['idaluno']));
            break;

        case 'listarMateriais':
            // Aqui está a nova função para o painel do aluno
            $idAluno = $_SESSION['idaluno'] ?? null;

            if (!$idAluno) {
                echo "Aluno não identificado na sessão.";
                exit;
            }

            try {
                $materiais = $material->buscarPorAluno($idAluno);
                include '../view/aluno/materiais.php'; // View com foreach que exibe os materiais
            } catch (Exception $e) {
                echo "Erro ao listar materiais: " . $e->getMessage();
            }
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
