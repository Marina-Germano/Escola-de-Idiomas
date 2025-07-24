<?php
session_start();
require_once "../models/idioma.php";
require_once "../models/nivel.php";
require_once "../models/tipo_material.php";
require_once "../models/turma.php";
require_once "../models/material.php";

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor']);
}

if (!estaLogado() || !temPermissao()) {
    header("Location: ../views/login.php");
    exit;
}

// Instanciando os models
$idiomaModel = new Idioma();
$nivelModel = new Nivel();
$tipoMaterialModel = new TipoMaterial();
$turmaModel = new Turma();
$materialModel = new Material();

$acao = $_GET['acao'] ?? '';

switch ($acao) {

    case 'cadastrar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $pdo = $materialModel->getPDO(); // Recupera a conexão para iniciar transação
                $pdo->beginTransaction();

                $descricao_idioma = $_POST['descricao_idioma'] ?? null;
                $descricao_nivel = $_POST['descricao_nivel'] ?? null;
                $descricao_tipo = $_POST['descricao_tipo_material'] ?? null;

                if (!$descricao_idioma || !$descricao_nivel || !$descricao_tipo) {
                    throw new Exception("Campos obrigatórios não foram preenchidos.");
                }

                $ididioma = $idiomaModel->buscarOuCriar($descricao_idioma);
                $idnivel = $nivelModel->buscarOuCriar($descricao_nivel);
                $idtipo_material = $tipoMaterialModel->buscarOuCriar($descricao_tipo);


                $descricao_turma = $_POST['descricao_turma'];
                $dias_semana = $_POST['dias_semana'];
                $hora_inicio = $_POST['hora_inicio'];
                $capacidade_maxima = $_POST['capacidade_maxima'];
                $sala = $_POST['sala'];
                $imagem = ''; // upload da imagem da turma (pode implementar depois)
                $idprofessor = $_POST['idprofessor'];
                $tipo_recorrencia = $_POST['tipo_recorrencia'] ?? null;
                $titulo = $_POST['titulo'];
                $descricao_material = $_POST['descricao_material'];
                $quantidade = $_POST['quantidade'];
                $formato_arquivo = $_POST['formato_arquivo'];

                // Upload do arquivo
                $arquivo = '';
                if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
                    $pasta = "../uploads/materiais/";
                    if (!is_dir($pasta)) mkdir($pasta, 0777, true);
                    $nomeArquivo = uniqid() . "_" . basename($_FILES['arquivo']['name']);
                    $destino = $pasta . $nomeArquivo;
                    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $destino)) {
                        $arquivo = 'uploads/' . $nomeArquivo;
                    } else {
                        throw new Exception('Erro ao mover o arquivo');
                    }
                }

                // Evita duplicação e retorna os IDs
                $ididioma = $idiomaModel->buscarOuCriar($descricao_idioma);
                $idnivel = $nivelModel->buscarOuCriar($descricao_nivel);
                $idtipo_material = $tipoMaterialModel->buscarOuCriar($descricao_tipo);

                $idturma = $turmaModel->cadastrar(
                    $ididioma, $idnivel, $descricao_turma, $dias_semana,
                    $hora_inicio, $capacidade_maxima, $sala, $imagem,
                    $idprofessor, $tipo_recorrencia
                );

                $materialModel->cadastrar(
                    $idtipo_material, $ididioma, $idnivel, $idturma,
                    $titulo, $descricao_material, $quantidade,
                    $formato_arquivo, $arquivo, $idprofessor
                );

                $pdo->commit();
                header("Location: ../views/components/sucesso.php?cadastrar=ok");
            exit;
            } catch (Exception $e) {
                $pdo->rollBack();
                die("Erro ao cadastrar material: " . $e->getMessage());
            }
        }
        break;

    case 'alterar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idmaterial = $_POST['idmaterial'];
            $idtipo_material = $_POST['idtipo_material'];
            $ididioma = $_POST['ididioma'];
            $idnivel = $_POST['idnivel'];
            $idturma = $_POST['idturma'];
            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];
            $quantidade = $_POST['quantidade'];
            $formato_arquivo = $_POST['formato_arquivo'];
            $idprofessor = $_POST['idprofessor'];

            $arquivo = ''; // padrão
            if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === 0) {
                $nome_arquivo = $_FILES['arquivo']['name'];
                $nome_temporario = $_FILES['arquivo']['tmp_name'];

                $diretorio_destino = '../uploads/';
                if (!is_dir($diretorio_destino)) mkdir($diretorio_destino, 0777, true);

                $novo_nome = uniqid() . '_' . basename($nome_arquivo);
                $caminho_completo = $diretorio_destino . $novo_nome;

                if (move_uploaded_file($nome_temporario, $caminho_completo)) {
                    $arquivo = 'uploads/' . $novo_nome;
                } else {
                    die('Erro ao mover o arquivo.');
                }
            }

            $materialModel->alterar(
                $idmaterial, $idtipo_material, $ididioma, $idnivel, $idturma,
                $titulo, $descricao, $quantidade, $formato_arquivo, $arquivo, $idprofessor
            );

            header("Location: ../views/components/sucesso.php?alterar=ok");
            exit;
        }
        break;

    case 'excluir':
        if (isset($_GET['idmaterial'])) {
            $idmaterial = $_GET['idmaterial'];
            $materialModel->excluir($idmaterial);
            header("Location: ../views/components/sucesso.php?excluir=ok");
            exit;
        }
        break;

    case 'listar':
        $materiais = $materialModel->listarTodos();
        include __DIR__ . "/../views/admin/listar_materiais.php";
        // exit;
        break;

    case 'listar_por_turma':
        if (isset($_GET['idturma'])) {
            $idturma = $_GET['idturma'];
            $materiais = $materialModel->listarPorTurma($idturma);
            include "../views/admin/listar_por_turma.php";
        }
        break;

    case 'listar_aluno':
        $idusuario = $_SESSION['idusuario'];
        $materiaisDoAluno = [];
        $erroMateriais = null;

        try {
            $aluno = $usuarioModel->buscarAlunoPorIdUsuario($idusuario);

            if ($aluno && isset($aluno['idaluno'])) {
                $idaluno = $aluno['idaluno'];
                $materiaisDoAluno = $materialModel->listarMateriaisPorAluno($idaluno);
                if (isset($materiaisDoAluno['error'])) {
                    $erroMateriais = $materiaisDoAluno['error'];
                    $materiaisDoAluno = [];
                }
            } else {
                $erroMateriais = "ID do aluno não encontrado para este usuário. Verifique se o usuário está associado a um aluno.";
            }

        } catch (Exception $e) {
            error_log("Erro no controlador de materiais (listar_aluno): " . $e->getMessage());
            $erroMateriais = "Ocorreu um erro ao carregar seus materiais. Tente novamente mais tarde.";
        }
        break;

    default:
        if ($papelUsuario === 'aluno') {
            header("Location: materialController.php?acao=listar_aluno");
        } else {
            header("Location: ../views/admin/dashboard.php");
        }
        exit;
}

include __DIR__ . '/../views/student/materiais.php';
