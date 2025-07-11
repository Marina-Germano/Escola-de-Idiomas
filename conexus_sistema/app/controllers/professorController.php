<?php
session_start();
require_once "../model/Professor.php";
require_once "../model/Funcionario.php";
require_once "../model/Usuario.php";

$professor = new Professor();
$funcionarioModel = new Funcionario();
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
            echo "Apenas administradores podem cadastrar professores.";
            exit;
        }

        // Recebendo dados do usuário
        $nome = $_POST['nome'] ?? null;
        $telefone = $_POST['telefone'] ?? null;
        $email = $_POST['email'] ?? null;
        $data_nascimento = $_POST['data_nascimento'] ?? null;
        $cpf = $_POST['cpf'] ?? null;
        $senha = $_POST['senha'] ?? null;

        // Dados do funcionário
        $cargo = $_POST['cargo'] ?? null;

        // Dados do professor
        $especialidade = $_POST['especialidade'] ?? null;

        // Validação
        if (!$nome || !$telefone || !$email || !$data_nascimento || !$cpf || !$senha || !$cargo || !$especialidade) {
            echo "Erro: Todos os campos são obrigatórios.";
            exit;
        }

        // Verifica se já existe usuário com esse CPF
        if ($usuarioModel->buscarPorCpf($cpf)) {
            echo "Erro: Já existe um usuário com esse CPF.";
            exit;
        }

        // Upload da foto (opcional, mas recomendado)
        $foto_nome = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $foto_nome = uniqid('foto_', true) . '.' . $ext;
            $destino = __DIR__ . "/../public/img/" . $foto_nome;
            move_uploaded_file($_FILES['foto']['tmp_name'], $destino);
        }

        // 1. Cadastrar o usuário com papel 'professor'
        $usuarioCriado = $usuarioModel->cadastrar(
            $nome,
            $telefone,
            $email,
            $data_nascimento,
            $cpf,
            $senha,
            'professor',
            $foto_nome
        );

        if (!$usuarioCriado) {
            echo "Erro ao cadastrar o usuário.";
            exit;
        }

        // 2. Recuperar idusuario
        $usuario = $usuarioModel->buscarPorCpf($cpf);
        $idusuario = $usuario['idusuario'] ?? null;

        if (!$idusuario) {
            echo "Erro ao recuperar ID do usuário.";
            exit;
        }

        // 3. Cadastrar funcionário vinculado ao usuário
        $funcionarioModel->cadastrar($idusuario, $cargo);

        // 4. Recuperar idfuncionario
        $idfuncionario = $funcionarioModel->buscarIdPorUsuario($idusuario);

        if (!$idfuncionario) {
            echo "Erro ao recuperar ID do funcionário.";
            exit;
        }

        // 5. Cadastrar professor vinculado ao funcionário
        $professor->cadastrar($idfuncionario, $especialidade);

        echo "Professor cadastrado com sucesso!";
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas administradores podem alterar professores.";
            exit;
        }

        $professor->alterar($_POST['idprofessor'], $_POST['especialidade']);
        echo "Professor alterado com sucesso!";
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas administradores podem excluir professores.";
            exit;
        }

        $professor->excluir($_GET['idprofessor']);
        echo "Professor excluído com sucesso!";
        break;

    case 'listarTodos':
        echo json_encode($professor->listarTodos());
        break;

    case 'listarId':
        echo json_encode($professor->listarId($_GET['idprofessor']));
        break;

    default:
        echo "Ação inválida.";
        break;
}
