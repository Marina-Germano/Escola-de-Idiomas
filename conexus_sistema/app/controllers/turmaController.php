<?php
session_start();
require_once "../model/Turma.php";

$turma = new Turma();

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

if (!isset($_GET['acao'])) {
    echo "Nenhuma ação definida.";
    exit;
}

function uploadImagem($arquivo) {
    $diretorio = '../uploads/turmas/';
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }

    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoesPermitidas)) {
        return ['erro' => 'Extensão de arquivo não permitida.'];
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        return ['erro' => 'Erro no upload do arquivo.'];
    }

    $nomeArquivo = uniqid() . '.' . $extensao;
    $caminhoDestino = $diretorio . $nomeArquivo;

    if (move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
        return ['nome' => $nomeArquivo];
    } else {
        return ['erro' => 'Falha ao mover o arquivo.'];
    }
}

$acao = $_GET['acao'];

switch ($acao) {
    case 'cadastrar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem cadastrar turmas.";
            exit;
        }

        $ididioma = $_POST['ididioma'] ?? null;
        $idnivel = $_POST['idnivel'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $dias_semana = $_POST['dias_semana'] ?? null;
        $hora_inicio = $_POST['hora_inicio'] ?? null;
        $capacidade_maxima = $_POST['capacidade_maxima'] ?? null;
        $sala = $_POST['sala'] ?? null;
        $idprofessor = $_POST['idprofessor'] ?? null;
        $tipo_recorrencia = $_POST['tipo_recorrencia'] ?? null;

        // Tratamento upload imagem
        $imagem = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = uploadImagem($_FILES['imagem']);
            if (isset($upload['erro'])) {
                echo "Erro no upload da imagem: " . $upload['erro'];
                exit;
            }
            $imagem = $upload['nome'];
        }

        if (!$ididioma || !$idnivel || !$descricao || !$dias_semana || !$hora_inicio || !$capacidade_maxima || !$sala || !$idprofessor) {
            echo "Erro: Preencha todos os campos obrigatórios.";
            exit;
        }

        $sucesso = $turma->cadastrar($ididioma, $idnivel, $descricao, $dias_semana, $hora_inicio, $capacidade_maxima, $sala, $imagem, $idprofessor, $tipo_recorrencia);
        if ($sucesso) {
            echo "Turma cadastrada com sucesso!";
        } else {
            echo "Erro ao cadastrar turma.";
        }
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem alterar turmas.";
            exit;
        }

        $idturma = $_POST['idturma'] ?? null;
        $ididioma = $_POST['ididioma'] ?? null;
        $idnivel = $_POST['idnivel'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $dias_semana = $_POST['dias_semana'] ?? null;
        $hora_inicio = $_POST['hora_inicio'] ?? null;
        $capacidade_maxima = $_POST['capacidade_maxima'] ?? null;
        $sala = $_POST['sala'] ?? null;
        $idprofessor = $_POST['idprofessor'] ?? null;
        $tipo_recorrencia = $_POST['tipo_recorrencia'] ?? null;

        if (!$idturma || !$ididioma || !$idnivel || !$descricao || !$dias_semana || !$hora_inicio || !$capacidade_maxima || !$sala || !$idprofessor) {
            echo "Erro: Preencha todos os campos obrigatórios.";
            exit;
        }

        // Upload imagem (se enviada)
        $imagem = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = uploadImagem($_FILES['imagem']);
            if (isset($upload['erro'])) {
                echo "Erro no upload da imagem: " . $upload['erro'];
                exit;
            }
            $imagem = $upload['nome'];
        } else {
            // Se não enviou nova imagem, pode querer manter a imagem antiga.
            // Para isso, busque a turma atual para pegar a imagem atual e passar adiante:
            $turmaAtual = $turma->listarId($idturma);
            $imagem = $turmaAtual['imagem'] ?? null;
        }

        $sucesso = $turma->alterar($idturma, $ididioma, $idnivel, $descricao, $dias_semana, $hora_inicio, $capacidade_maxima, $sala, $imagem, $idprofessor, $tipo_recorrencia);
        if ($sucesso) {
            echo "Turma alterada com sucesso!";
        } else {
            echo "Erro ao alterar turma.";
        }
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado. Apenas usuários autorizados podem excluir turmas.";
            exit;
        }

        $idturma = $_GET['idturma'] ?? null;
        if (!$idturma) {
            echo "Erro: ID da turma não informado.";
            exit;
        }

        $sucesso = $turma->excluir($idturma);
        if ($sucesso) {
            echo "Turma excluída com sucesso!";
        } else {
            echo "Erro ao excluir turma.";
        }
        break;

    case 'listarTodos':
        echo json_encode($turma->listarTodos());
        break;

    case 'listarId':
        $idturma = $_GET['idturma'] ?? null;
        if (!$idturma) {
            echo "Erro: ID da turma não informado.";
            exit;
        }
        echo json_encode($turma->listarId($idturma));
        break;

    default:
        echo "Ação inválida.";
        break;
}
