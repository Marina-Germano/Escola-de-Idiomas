<?php
session_start();
require_once "../models/aluno.php";
require_once "../models/usuario.php";
require_once "../models/material.php";

$aluno = new Aluno();
$usuarioModel = new Usuario();
$material = new Material();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

// Apenas admin e funcionário podem cadastrar alunos
function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor']);
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
    case 'cadastrarCompleto':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem cadastrar alunos.";
            exit;
        }

        // Dados do usuário
        $nome = $_POST['nome'] ?? null;
        $telefone = $_POST['telefone'] ?? null;
        $email = $_POST['email'] ?? null;
        $data_nascimento = $_POST['data_nascimento'] ?? null;
        $cpf = $_POST['cpf'] ?? null;
        $senha = $_POST['senha'] ?? null;

        // Dados do aluno
        $cep = $_POST['cep'] ?? null;
        $rua = $_POST['rua'] ?? null;
        $numero = $_POST['numero'] ?? null;
        $bairro = $_POST['bairro'] ?? null;
        $complemento = $_POST['complemento'] ?? null;
        $responsavel = $_POST['responsavel'] ?? null;
        $tel_responsavel = $_POST['tel_responsavel'] ?? null;
        $situacao = $_POST['situacao'] ?? 'ativo';

        // Validação básica
        if (!$nome || !$telefone || !$email || !$data_nascimento || !$cpf || !$senha || !$cep || !$rua || !$numero || !$bairro || !$responsavel || !$tel_responsavel) {
            echo "Erro: Todos os campos obrigatórios devem ser preenchidos.";
            exit;
        }

        // Verifica se já existe usuário com esse CPF
        if ($usuarioModel->buscarPorCpf($cpf)) {
            echo "Erro: Já existe um usuário com esse CPF.";
            exit;
        }

        // Definir imagem padrão
        $foto_nome = 'user.png';


        // 1. Cadastrar usuário
        $cadastroUsuario = $usuarioModel->cadastrar(
            $nome,
            $telefone,
            $email,
            $data_nascimento,
            $cpf,
            $senha,
            'aluno',
            $foto_nome
        );

        if (!$cadastroUsuario) {
            echo "Erro ao cadastrar o usuário.";
            exit;
        }

        // 2. Obter o idusuario
        $usuario = $usuarioModel->buscarPorCpf($cpf);
        $idusuario = $usuario['idusuario'] ?? null;

        if (!$idusuario) {
            echo "Erro ao recuperar ID do usuário.";
            exit;
        }

        // 3. Cadastrar aluno
        $aluno->cadastrar(
            $idusuario,
            $cep,
            $rua,
            $numero,
            $bairro,
            $complemento,
            $responsavel,
            $tel_responsavel,
            $situacao
        );
        
        header("Location: ../views/admin/dashboard.php");
            exit;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem alterar alunos.";
            exit;
        }

        $aluno->alterar(
            $_POST['idaluno'],
            $_POST['cep'],
            $_POST['rua'],
            $_POST['numero'],
            $_POST['bairro'],
            $_POST['complemento'],
            $_POST['responsavel'],
            $_POST['tel_responsavel'],
            $_POST['situacao']
        );
        header("Location: ../views/admin/dashboard.php");
            exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem excluir alunos.";
            exit;
        }
        $aluno->excluir($_GET['id']);
        header("Location: ../views/admin/list_students.php");
            exit;

        case 'listarTodos':
            echo json_encode($aluno->listarTodos());
            break;
            
        case 'listarId':
        echo json_encode($aluno->listarId($_GET['idaluno']));
        break;
        
        case 'listarMateriais':
        // Acesso exclusivo do aluno aos materiais
        $idaluno = $_SESSION['idaluno'] ?? null;
        
        if (!$idaluno) {
            http_response_code(403);
            echo "Aluno não identificado na sessão.";
            exit;
        }
        
        $materiais = $material->listar($idaluno);
        include '../view/student/material.php';
        break;
        
        default:
        echo "Ação inválida.";
        break;
    }
