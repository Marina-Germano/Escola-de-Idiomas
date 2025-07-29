<?php
session_start();
require_once "../models/material.php";
require_once "../models/aluno.php";
require_once "../config/conexao.php";

$material = new Material();
$alunoModel = new Aluno();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor', 'aluno']);
}

function redirecionarUsuario() {
    $papel = $_SESSION['papel'];
    if ($papel === 'admin') {
        header("Location: ../views/admin/list_material.php");
    } elseif ($papel === 'professor') {
        header("Location: ../views/teacher/list_material.php");
    } else {
        header("Location: ../views/list_material.php");
    }
    exit;
}

if (!estaLogado()) {
    http_response_code(401);
    echo "Acesso negado. Faça login para continuar.";
    exit;
}

$acao = $_GET['acao'] ?? null;
if (!$acao && $_SESSION['papel'] === 'aluno') {
    $acao = 'listar_materiais_aluno_view';
}

$acao = $_GET['acao'] ; //?? 'listar_materiais_aluno_view';

switch ($acao) {
    case 'cadastrar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem cadastrar materiais.";
            exit;
        }

        if (!isset($_POST['idturma']) || empty($_POST['idturma'])) {
            $_POST['idturma'] = null;
        }

        $ok = $material->cadastrar([
            'idtipo_material'   => $_POST['idtipo_material'],
            'ididioma'          => $_POST['ididioma'],
            'idnivel'           => $_POST['idnivel'],
            'idturma'           => $_POST['idturma'],
            'titulo'            => $_POST['titulo'],
            'descricao'         => $_POST['descricao'] ?? "",
            'quantidade'        => $_POST['quantidade'],
            'formato_arquivo'   => $_POST['formato_arquivo'] ?? "",
            'arquivo'           => $_POST['arquivo'] ?? ""
        ]);
        redirecionarUsuario();
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar materiais.";
            exit;
        }

        $ok = $material->alterar($_POST['idmaterial'], [
            'idtipo_material'   => $_POST['idtipo_material'],
            'ididioma'          => $_POST['ididioma'],
            'idnivel'           => $_POST['idnivel'],
            'idturma'           => $_POST['idturma'],
            'titulo'            => $_POST['titulo'],
            'descricao'         => $_POST['descricao'] ?? "",
            'quantidade'        => $_POST['quantidade'],
            'formato_arquivo'   => $_POST['formato_arquivo'] ?? "",
            'arquivo'           => $_POST['arquivo'] ?? ""
        ]);
        redirecionarUsuario();
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir materiais.";
            exit;
        }
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "ID do material não informado.";
            exit;
        }
        $ok = $material->excluir($_GET['id']);
        redirecionarUsuario();
        break;

    case 'listar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        echo json_encode($material->listar());
        break;

    case 'buscarPorId':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "ID do material não informado.";
            exit;
        }
        echo json_encode($material->buscarPorId($_GET['id']));
        break;

    case 'listarPorAluno':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['idaluno'])) {
            http_response_code(400);
            echo "ID do aluno não informado.";
            exit;
        }
        echo json_encode($material->listarMateriaisPorAluno($_GET['idaluno']));
        break;

    case 'ultimosPorAluno':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['idaluno'])) {
            http_response_code(400);
            echo "ID do aluno não informado.";
            exit;
        }
        $limite = $_GET['limite'] ?? 6;
        echo json_encode($material->getUltimosMateriaisPorAluno($_GET['idaluno'], $limite));
        break;

    case 'listarPorTurma':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['idturma'])) {
            http_response_code(400);
            echo "ID da turma não informado.";
            exit;
        }
        echo json_encode($material->listarPorTurma($_GET['idturma']));
        break;

    case 'listar_materiais_aluno_view': 
    case 'listar_aluno': // Adicionado para compatibilidade com a URL que você está usando
        $idusuario = $_SESSION['idusuario'];
        $idalunoLogado = null;

        try {
            $pdo = Conexao::conectar(); // Reutilize a conexão
            $stmt = $pdo->prepare("SELECT idaluno FROM aluno WHERE idusuario = :idusuario");
            $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
            $stmt->execute();
            $resultadoAluno = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultadoAluno) {
                $idalunoLogado = $resultadoAluno['idaluno'];
            } else {
                error_log("ERRO: idusuario " . $idusuario . " não encontrado na tabela aluno.");
                header('Location: /escola-de-idiomas/conexus_sistema/app/views/login.php'); 
                exit;
            }
        } catch (PDOException $e) {
            error_log("ERRO PDO ao buscar idaluno: " . $e->getMessage());
            header('Location: /escola-de-idiomas/conexus_sistema/app/views/erro.php'); 
            exit;
        }

        if ($idalunoLogado) {
            $materiaisDoAluno = $material->listarMateriaisPorAluno($idalunoLogado);
            if (isset($materiaisDoAluno['error'])) {
                error_log("Erro ao carregar materiais para o aluno " . $idalunoLogado . ": " . $materiaisDoAluno['error']);
                $materiaisDoAluno = []; 
            }
        } else {
            $materiaisDoAluno = [];
            error_log("ID do aluno não encontrado para o usuário logado.");
        }
        
        
        include __DIR__ . '/../views/student/material.php';
        break;

    default:
        echo "Ação inválida.";
        break;
}