<?php

session_start();

require_once __DIR__ . "/../models/contato.php"; 
require_once __DIR__ . "/../config/conexao.php"; 

$contatoModel = new Contato(); 

// permissão de manipulação (CRUD)
function temPermissaoManipulacao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario']);
}

// verifica login
if (!isset($_SESSION['idusuario']) || ($_SESSION['papel'] !== 'aluno' && $_SESSION['papel'] !== 'admin')) {
    header('Location: /escola-de-idiomas/conexus_sistema/app/views/login.php');
    exit();
}

// Pega o ID do usuário e o papel da sessão.
$idUsuarioLogado = $_SESSION['idusuario'];
$papelUsuarioLogado = $_SESSION['papel'];
error_log("DEBUG: idusuario logado: " . $idUsuarioLogado . ", Papel: " . $papelUsuarioLogado);

// formulário de contato
if (isset($_POST['submit_contato'])) {
    $idusuario = $idUsuarioLogado; 
    $nome = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $telefone = htmlspecialchars($_POST['number'] ?? '');
    $matricula = htmlspecialchars($_POST['matricula'] ?? ''); // Esta variável não é usada no método cadastrar do modelo Contato
    $motivo_contato = htmlspecialchars($_POST['razao'] ?? '');
    $mensagem = htmlspecialchars($_POST['msg'] ?? '');
    $arquivo = null; 

    // lógica para upload de arquivo
    if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../uploads/contatos/'; 
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); 
        }
        
        $fileName = basename($_FILES['anexo']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['anexo']['tmp_name'], $uploadFile)) {
            $arquivo = '/uploads/contatos/' . $fileName; 
        } else {
            $_SESSION['mensagem_contato'] = "Erro ao fazer upload do arquivo. Tente novamente.";
        }
    }

    //  campos do formulário
    if (empty($nome) || empty($email) || empty($telefone) || empty($motivo_contato)) {
        $_SESSION['mensagem_contato'] = "Por favor, preencha todos os campos obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensagem_contato'] = "Formato de e-mail inválido. Por favor, verifique.";
    } else {
        // cadastrar do modelo
        $ok = $contatoModel->cadastrar($idusuario, $nome, $email, $telefone, $arquivo, $motivo_contato, $mensagem);

        if ($ok) {
            $_SESSION['mensagem_contato'] = "Sua mensagem foi enviada com sucesso! Aguarde nosso contato.";
        } else {
            $_SESSION['mensagem_contato'] = "Erro ao enviar sua mensagem. Não foi possível registrar. Tente novamente mais tarde.";
        }
    }
    
    header('Location: ../views/student/contact.php');
    exit;
}

// prepara variáveis para preencher os campos do formulário na view
$nomeCampo = $_SESSION['nome_usuario'] ?? '';
$matriculaCampo = $_SESSION['matricula'] ?? '';

// recupera e limpa a mensagem de feedback da sessão
$mensagemFeedback = '';
if (isset($_SESSION['mensagem_contato'])) {
    $mensagemFeedback = $_SESSION['mensagem_contato'];
    unset($_SESSION['mensagem_contato']);
}

//  processar CRUD
if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    switch ($acao) {
        case 'listarMeusContatos':
            if ($papelUsuarioLogado === 'aluno' || temPermissaoManipulacao()) {
                header('Content-Type: application/json');
                echo json_encode($contatoModel->listarPorUsuario($idUsuarioLogado));
            } else {
                http_response_code(403);
                echo "Acesso negado. Você só pode listar seus próprios contatos.";
            }
            break;

        case 'cadastrar':
            http_response_code(400);
            echo "Método de requisição inválido para cadastrar um novo contato por esta rota.";
            break;

        case 'alterar':
            if (!temPermissaoManipulacao()) { 
                http_response_code(403);
                echo "Apenas usuários autorizados podem alterar contatos.";
                break;
            }
            $idcontato = $_POST['idcontato'] ?? null;
            $idusuario = $_POST['idusuario'] ?? null;
            $nome = htmlspecialchars($_POST['nome'] ?? '');
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $telefone = htmlspecialchars($_POST['telefone'] ?? '');
            $arquivo = htmlspecialchars($_POST['arquivo'] ?? null);
            $motivo_contato = htmlspecialchars($_POST['motivo_contato'] ?? '');
            $mensagem = htmlspecialchars($_POST['mensagem'] ?? '');
            $status = htmlspecialchars($_POST['status'] ?? 'pendente');

            if (!$idcontato || empty($nome) || empty($email) || empty($motivo_contato) || empty($status)) {
                http_response_code(400);
                echo "Dados insuficientes para alterar contato.";
                break;
            }

            $ok = $contatoModel->alterar($idcontato, $idusuario, $nome, $email, $telefone, $arquivo, $motivo_contato, $mensagem, $status);

            echo $ok ? "Contato alterado com sucesso!" : "Erro ao alterar contato.";
            break;

        case 'excluir':
            if (!temPermissaoManipulacao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem excluir contatos.";
                break;
            }
            if (!isset($_GET['idcontato'])) {
                http_response_code(400);
                echo "ID do contato não informado.";
                break;;
            }
            $idcontato = $_GET['idcontato'];
            $ok = $contatoModel->excluir($idcontato);
            echo $ok ? "Contato excluído com sucesso!" : "Erro ao excluir contato.";
            break;

        case 'listarTodos':
            if (!temPermissaoManipulacao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem listar todos os contatos.";
                break;
            }
            header('Content-Type: application/json');
            echo json_encode($contatoModel->listarTodos());
            break;

        case 'listarId':
            if (!temPermissaoManipulacao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem ver detalhes do contato.";
                break;
            }
            if (!isset($_GET['idcontato'])) {
                http_response_code(400);
                echo "ID do contato não informado.";
                break;
            }
            header('Content-Type: application/json');
            echo json_encode($contatoModel->listarId($_GET['idcontato']));
            break; 

        default:
            http_response_code(400);
            echo "Ação inválida ou não suportada.";
            break;
    }
}
include __DIR__ . '/../views/student/contact.php';

?>
