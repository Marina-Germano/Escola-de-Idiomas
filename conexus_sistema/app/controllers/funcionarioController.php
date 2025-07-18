<?php
session_start();
require_once "../models/Funcionario.php";
require_once "../models/Usuario.php";

$funcionario = new Funcionario();
$usuarioModel = new Usuario();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && $_SESSION['papel'] === 'admin';
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
            echo "Apenas administradores podem cadastrar funcionários.";
            exit;
        }

        // Coleta os dados do POST
        $nome = $_POST['nome'] ?? null;
        $telefone = $_POST['telefone'] ?? null;
        $email = $_POST['email'] ?? null;
        $data_nascimento = $_POST['data_nascimento'] ?? null;
        $cpf = $_POST['cpf'] ?? null;
        $senha = $_POST['senha'] ?? null;
        $cargo = $_POST['cargo'] ?? null;

        // Validação básica
        if (!$nome || !$telefone || !$email || !$data_nascimento || !$cpf || !$senha || !$cargo) {
            echo "Erro: Todos os campos são obrigatórios.";
            exit;
        }

        // Verifica se já existe um usuário com esse CPF
        if ($usuarioModel->buscarPorCpf($cpf)) {
            echo "Erro: Já existe um usuário com esse CPF.";
            exit;
        }

        // Upload da foto
        $foto_nome = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto_nome = uniqid('foto_', true) . '.' . $extensao;
            $caminho = __DIR__ . '../../public/img/' . $foto_nome;
            move_uploaded_file($_FILES['foto']['tmp_name'], $caminho);
        }

        // 1. Cadastra o usuário com papel = funcionario
        $cadastroUsuario = $usuarioModel->cadastrar(
            $nome,
            $telefone,
            $email,
            $data_nascimento,
            $cpf,
            $senha,
            'funcionario',
            $foto_nome
        );

        if (!$cadastroUsuario) {
            echo "Erro ao cadastrar o usuário.";
            exit;
        }

        // 2. Recupera o idusuario recém cadastrado
        $usuario = $usuarioModel->buscarPorCpf($cpf);
        $idusuario = $usuario['idusuario'] ?? null;

        if (!$idusuario) {
            echo "Erro ao recuperar ID do usuário.";
            exit;
        }

        // 3. Cadastra o funcionário
        $funcionario->cadastrar($idusuario, $cargo);

        echo "Funcionário cadastrado com sucesso!";
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas administradores podem alterar funcionários.";
            exit;
        }

        $funcionario->alterar($_POST['idfuncionario'], $_POST['cargo']);
        echo "Funcionário alterado com sucesso!";
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas administradores podem excluir funcionários.";
            exit;
        }

        $funcionario->excluir($_GET['idfuncionario']);
        echo "Funcionário excluído com sucesso!";
        break;

    case 'listarTodos':
        echo json_encode($funcionario->listarTodos());
        break;

    case 'listarId':
        echo json_encode($funcionario->listarId($_GET['idfuncionario']));
        break;

    default:
        echo "Ação inválida.";
        break;
}
