<?php
session_start();
require_once "../models/contato.php";

$contato = new Contato();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    // Usuários com papel admin, funcionario podem listar e gerenciar contatos
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'aluno']);
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
            // Qualquer usuário logado pode cadastrar um contato (ex: enviar mensagem)
            $idusuario = $_SESSION['idusuario'];
            $nome = $_POST['nome'] ?? null;
            $email = $_POST['email'] ?? null;
            $telefone = $_POST['telefone'] ?? null;
            $arquivo = $_FILES['arquivo']['name'] ?? null;
            $motivo_contato = $_POST['motivo_contato'] ?? null;
            $mensagem = $_POST['mensagem'] ?? null;

            // Aqui você pode tratar upload do arquivo se existir
            if ($arquivo && isset($_FILES['arquivo'])) {
                $uploadDir = '../uploads/contatos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $uploadFile = $uploadDir . basename($_FILES['arquivo']['name']);
                if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadFile)) {
                    $arquivo = '/uploads/contatos/' . basename($_FILES['arquivo']['name']);
                } else {
                    $arquivo = null; // upload falhou
                }
            }

            if ($contato->cadastrar($idusuario, $nome, $email, $telefone, $arquivo, $motivo_contato, $mensagem)) {
                header("Location: ../views/admin/sucesso.php?cadastrar=ok");
            exit;

            } else {
                http_response_code(500);
                echo "Erro ao cadastrar contato.";
            }
            header("Location: ../views/components/sucesso.php?cadastrar=ok");
            exit;

        case 'alterar':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem alterar contatos.";
                exit;
            }
            $idcontato = $_POST['idcontato'];
            $idusuario = $_POST['idusuario'];
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $arquivo = $_POST['arquivo'] ?? null;
            $motivo_contato = $_POST['motivo_contato'];
            $mensagem = $_POST['mensagem'];

            if ($contato->alterar($idcontato, $idusuario, $nome, $email, $telefone, $arquivo, $motivo_contato, $mensagem)) {
                header("Location: ../views/admin/sucesso.php?alterar=ok");
            exit;

            } else {
                http_response_code(500);
                echo "Erro ao alterar contato.";
            }
            header("Location: ../views/components/sucesso.php?alterar=ok");
            exit;

        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem excluir contatos.";
                exit;
            }
            $idcontato = $_GET['idcontato'];
            if ($contato->excluir($idcontato)) {
                echo "Contato excluído com sucesso!";
            } else {
                http_response_code(500);
                echo "Erro ao excluir contato.";
            }
            header("Location: ../views/components/sucesso.php?excluir=ok");
            exit;

        case 'listarTodos':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem listar contatos.";
                exit;
            }
            echo json_encode($contato->listarTodos());
            break;

        case 'listarId':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Apenas usuários autorizados podem ver detalhes do contato.";
                exit;
            }
            $idcontato = $_GET['idcontato'];
            echo json_encode($contato->listarId($idcontato));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
