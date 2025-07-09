<?php
session_start();
require_once "../model/Usuario.php";
require_once "../model/Aluno.php";
require_once "../model/Professor.php";
require_once "../model/Funcionario.php";

$usuario = new Usuario();
$aluno = new Aluno();
$professor = new Professor();
$funcionario = new Funcionario();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario']);
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
                echo "Apenas usuários autorizados podem cadastrar usuários.";
                exit;
            }

            // Recebe os dados do POST
            $nome = $_POST['nome'] ?? null;
            $telefone = $_POST['telefone'] ?? null;
            $email = $_POST['email'] ?? null;
            $data_nascimento = $_POST['data_nascimento'] ?? null;
            $cpf = $_POST['cpf'] ?? null;
            $senha = $_POST['senha'] ?? null;
            $papel = $_POST['papel'] ?? null;

            // Verifica se todos os campos obrigatórios foram informados
            if (!$nome || !$telefone || !$email || !$data_nascimento || !$cpf || !$senha || !$papel) {
                echo "Erro: Todos os campos são obrigatórios.";
                exit;
            }

            // Verifica se o CPF já está cadastrado no sistema
            $usuarioExistente = $usuario->buscarPorCpf($cpf);
            if ($usuarioExistente) {
                echo "Erro: CPF já cadastrado no sistema.";
                exit;
            }

            // // Opcional: verificar se CPF existe nas tabelas reais (aluno, professor, funcionario)
            // $idaluno = $aluno->buscarIdPorCpf($cpf);
            // $idprofessor = $professor->buscarIdPorCpf($cpf);
            // $idfuncionario = $funcionario->buscarIdPorCpf($cpf);

            if ($papel === 'aluno' && !$idaluno) {
                echo "Erro: CPF informado não pertence a nenhum aluno cadastrado.";
                exit;
            }

            if ($papel === 'professor' && !$idprofessor) {
                echo "Erro: CPF informado não pertence a nenhum professor cadastrado.";
                exit;
            }

            if ($papel === 'funcionario' && !$idfuncionario) {
                echo "Erro: CPF informado não pertence a nenhum funcionário cadastrado.";
                exit;
            }

            // Cadastro do usuário
            $usuario->cadastrar(
                $nome,
                $telefone,
                $email,
                $data_nascimento,
                $cpf,
                $senha,
                $papel
            );

            echo "Usuário cadastrado com sucesso!";
            break;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem alterar usuários.";
                exit;
            }

            $usuario->alterar(
                $_POST['idusuario'],
                $_POST['nome'],
                $_POST['telefone'],
                $_POST['email'],
                $_POST['data_nascimento'],
                $_POST['cpf'],
                $_POST['senha'],
                $_POST['papel'],
                $_POST['ativo'] ?? true,
                $_POST['tentativas_login'] ?? 0,
                $_POST['bloqueado'] ?? false
            );

            echo "Usuário alterado com sucesso!";
            break;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem excluir usuários.";
                exit;
            }

            $usuario->excluir($_GET['idusuario']);
            echo "Usuário excluído com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($usuario->listarTodos());
            break;

        case 'listarId':
            echo json_encode($usuario->listarId($_GET['idusuario']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
