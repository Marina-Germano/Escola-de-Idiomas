<?php
session_start();
require_once __DIR__ . '/../models/material.php';
require_once __DIR__ .'/../models/aluno.php';
require_once __DIR__ .'/../models/idioma.php';
require_once __DIR__ .'/../models/nivel.php';
require_once __DIR__ .'/../models/turma.php';
require_once __DIR__ .'/../models/aluno_turma.php';
require_once __DIR__ .'/../models/funcionario.php';
require_once __DIR__ .'/../models/tipo_material.php';
function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    // Permite admin e funcionario gerenciar Materials
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor']);
}

if (!estaLogado()) {
    http_response_code(401);
    header('Location: ../views/login.php');
    exit;
}

$idiomaModel = new Idioma();
$nivelModel = new nivel();
$turmaModel = new Turma();
$alunoTurmaModel = new AlunoTurma();
$funcionarioModel = new Funcionario();
$tipoMaterialModel = new TipoMaterial();
$materialModel = new Material();

switch ($acao) {
    
case 'cadastrar':
    $material = new Material();

    $titulo = $_POST['titulo'] ?? null;
    $descricao = $_POST['descricao'] ?? null;
    $quantidade = $_POST['quantidade'] ?? null;
    $formato_arquivo = $_POST['formato_arquivo'] ?? null;
    $ididioma = $_POST['ididioma'] ?? null;
    $idnivel = $_POST['idnivel'] ?? null;
    $idtipo_material = $_POST['idtipo_material'] ?? null;
    $idturma = $_POST['idturma'] ?? null;
    $idfuncionario = $_POST['idfuncionario'] ?? null;

    // Debug (remova após teste)
    // var_dump($_POST); exit;

    $material->cadastrar(
        $titulo,
        $descricao,
        $quantidade,
        $formato_arquivo,
        $ididioma,
        $idnivel,
        $idtipo_material,
        $idturma,
        $idfuncionario
    );
    break;


    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar Materials.";
            exit;
        }

        $ok = $materialModel->alterar(
            $_POST['idmaterial'],
            $_POST['idtipo_material'],
            $_POST['ididioma'],
            $_POST['idnivel'],
            $_POST['idturma'],
            $_POST['titulo'],
            $_POST['descricao'],
            $_POST['quantidade'],
            $_POST['formato_arquivo'],
            $_POST['idfuncionario']
            
        );

        echo $ok ? "Material alterado com sucesso!" : "Erro ao alterar Material.";
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir Materials.";
            exit;
        }
        if (!isset($_GET['idmaterial'])) {
            http_response_code(400);
            echo "ID do Material não informado.";
            exit;
        }
        $ok = $materialModel->excluir($_GET['idmaterial']);
        echo $ok ? "Material excluído com sucesso!" : "Erro ao excluir Material.";
        break;

    case 'listarTodos':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        echo json_encode($materialModel->listarTodos());
        break;

    case 'listarId':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['idmaterial'])) {
            http_response_code(400);
            echo "ID do Material não informado.";
            exit;
        }
        echo json_encode($materialModel->listarId($_GET['idmaterial']));
        break;

    default:
        echo "Ação inválida.";
        break;
}
