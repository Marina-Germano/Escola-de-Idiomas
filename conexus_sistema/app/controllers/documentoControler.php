<?php
session_start();
require_once "../models/documento.php";

$documentoAluno = new DocumentoAluno();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario']);
}

if (!estaLogado()) {
    http_response_code(401);
    echo "Acesso negado. Faça login.";
    exit;
}

if (!isset($_GET['acao'])) {
    echo "Nenhuma ação definida.";
    exit;
}

$acao = $_GET['acao'];

switch ($acao) {
    case 'cadastrar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem cadastrar documentos.";
            exit;
        }

        $idaluno = $_POST['idaluno'] ?? null;
        $idtipo_documento = $_POST['idtipo_documento'] ?? null;
        $observacoes = $_POST['observacoes'] ?? '';
        $status_documento = $_POST['status_documento'] ?? 'pendente';

        // Verifica o envio de arquivo
        if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
            echo "Erro no upload do arquivo.";
            exit;
        }

        // Processa e move o arquivo
        $pastaDestino = "../uploads/documentos/";
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0777, true);
        }

        $nomeOriginal = basename($_FILES['arquivo']['name']);
        $caminho_arquivo = $pastaDestino . uniqid() . "_" . $nomeOriginal;

        if (!move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_arquivo)) {
            echo "Erro ao salvar o arquivo.";
            exit;
        }

        $documentoAluno->cadastrar($idaluno, $idtipo_documento, $caminho_arquivo, $observacoes, $status_documento);
        header("Location: ../views/components/sucess.php?cadastrar=ok");
            exit;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar documentos.";
            exit;
        }

        $iddocumento = $_POST['iddocumento'] ?? null;
        $observacoes = $_POST['observacoes'] ?? '';
        $status_documento = $_POST['status_documento'] ?? 'pendente';

        $caminho_arquivo = $_POST['caminho_atual'] ?? null;

        // Se um novo arquivo foi enviado, substitui
        if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
            $pastaDestino = "../uploads/documentos/";
            if (!is_dir($pastaDestino)) {
                mkdir($pastaDestino, 0777, true);
            }

            $nomeOriginal = basename($_FILES['arquivo']['name']);
            $caminho_arquivo = $pastaDestino . uniqid() . "_" . $nomeOriginal;

            if (!move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_arquivo)) {
                echo "Erro ao salvar o novo arquivo.";
                exit;
            }
        }

        $documentoAluno->alterar($iddocumento, $caminho_arquivo, $observacoes, $status_documento);
        header("Location: ../views/components/sucess.php?alterar=ok");
            exit;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir documentos.";
            exit;
        }

        $id = $_GET['iddocumento'] ?? null;
        if (!$id) {
            echo "ID do documento não informado.";
            exit;
        }

        $documentoAluno->excluir($id);
        header("Location: ../views/components/sucess.php?excluir=ok");
        exit;

    case 'listarTodos':
        echo json_encode($documentoAluno->listarTodos());
        break;

    case 'listarId':
        $id = $_GET['iddocumento'] ?? null;
        if (!$id) {
            echo "ID do documento não informado.";
            exit;
        }

        echo json_encode($documentoAluno->listarId($id));
        break;

    default:
        echo "Ação inválida.";
        break;
}
