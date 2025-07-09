<?php
session_start();
require_once "../model/Material.php";

$material = new Material();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor']);
}

function isAluno() {
    return isset($_SESSION['papel']) && $_SESSION['papel'] === 'aluno';
}

// Função para tratar upload de arquivo
function uploadArquivo($campoFile) {
    if (!isset($_FILES[$campoFile]) || $_FILES[$campoFile]['error'] !== UPLOAD_ERR_OK) {
        return null; // Nenhum arquivo enviado ou erro no upload
    }

    $pastaDestino = "../uploads/materiais/"; // Ajuste conforme sua estrutura
    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0755, true);
    }

    $nomeOriginal = basename($_FILES[$campoFile]["name"]);
    $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
    $novoNome = uniqid('mat_') . "." . $extensao;
    $caminhoCompleto = $pastaDestino . $novoNome;

    if (move_uploaded_file($_FILES[$campoFile]["tmp_name"], $caminhoCompleto)) {
        // Retorna o caminho relativo que será salvo no banco
        return "uploads/materiais/" . $novoNome;
    } else {
        return null; // Falha ao mover arquivo
    }
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
    case 'cadastrar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem cadastrar materiais.";
            exit;
        }

        $arquivoSalvo = uploadArquivo('arquivo');
        if ($arquivoSalvo === null) {
            http_response_code(400);
            echo "Erro no upload do arquivo.";
            exit;
        }

        $ok = $material->cadastrar(
            $_POST['idtipo_material'],
            $_POST['ididioma'],
            $_POST['idnivel'],
            $_POST['idturma'],
            $_POST['titulo'],
            $_POST['descricao'],
            $_POST['quantidade'],
            $arquivoSalvo ? pathinfo($arquivoSalvo, PATHINFO_EXTENSION) : null,
            $arquivoSalvo,
            $_POST['idprofessor']
        );

        echo $ok ? "Material cadastrado com sucesso!" : "Erro ao cadastrar material.";
        break;

    case 'alterar':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar materiais.";
            exit;
        }

        $arquivoSalvo = uploadArquivo('arquivo');

        // Se nenhum arquivo enviado, mantém o arquivo atual (buscar no banco)
        if ($arquivoSalvo === null) {
            $matAtual = $material->listarId($_POST['idmaterial']);
            $arquivoSalvo = $matAtual['arquivo'];
            $extensao = $matAtual['formato_arquivo'];
        } else {
            $extensao = pathinfo($arquivoSalvo, PATHINFO_EXTENSION);
        }

        $ok = $material->alterar(
            $_POST['idmaterial'],
            $_POST['idtipo_material'],
            $_POST['ididioma'],
            $_POST['idnivel'],
            $_POST['idturma'],
            $_POST['titulo'],
            $_POST['descricao'],
            $_POST['quantidade'],
            $extensao,
            $arquivoSalvo,
            $_POST['idprofessor']
        );

        echo $ok ? "Material alterado com sucesso!" : "Erro ao alterar material.";
        break;

    case 'excluir':
        if (!temPermissao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem excluir materiais.";
            exit;
        }
        if (!isset($_GET['idmaterial'])) {
            http_response_code(400);
            echo "ID do material não informado.";
            exit;
        }
        $ok = $material->excluir($_GET['idmaterial']);
        echo $ok ? "Material excluído com sucesso!" : "Erro ao excluir material.";
        break;

    case 'listarTodos':
        if (!temPermissao() && !isAluno()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        echo json_encode($material->listarTodos());
        break;

    case 'listarId':
        if (!temPermissao() && !isAluno()) {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
        if (!isset($_GET['idmaterial'])) {
            http_response_code(400);
            echo "ID do material não informado.";
            exit;
        }
        echo json_encode($material->listarId($_GET['idmaterial']));
        break;

    case 'listarPorAluno':
        if (!isAluno()) {
            http_response_code(403);
            echo "Apenas alunos podem acessar seus materiais.";
            exit;
        }
        echo json_encode($material->listarMateriaisPorAluno($_SESSION['idusuario'])); // supõe que idusuario == idaluno
        break;

    case 'download':
        if (!isAluno() && !temPermissao()) {
            http_response_code(403);
            echo "Acesso negado para download.";
            exit;
        }
        if (!isset($_GET['idmaterial'])) {
            http_response_code(400);
            echo "ID do material não informado.";
            exit;
        }
        $mat = $material->listarId($_GET['idmaterial']);
        if (!$mat || empty($mat['arquivo'])) {
            http_response_code(404);
            echo "Arquivo não encontrado.";
            exit;
        }

        // Se aluno, verificar se tem permissão (está matriculado na turma)
        if (isAluno()) {
            // Você pode criar método no Model para validar acesso (exemplo):
            // Verificar se aluno está matriculado na turma do material
            $temAcesso = false;
            $alunoId = $_SESSION['idusuario'];

            // Supondo que $material->listarMateriaisPorAluno() retorna os materiais do aluno
            $materiaisAluno = $material->listarMateriaisPorAluno($alunoId);
            foreach ($materiaisAluno as $m) {
                if ($m['idmaterial'] == $_GET['idmaterial']) {
                    $temAcesso = true;
                    break;
                }
            }
            if (!$temAcesso) {
                http_response_code(403);
                echo "Você não tem permissão para acessar este arquivo.";
                exit;
            }
        }

        $filepath = "../" . $mat['arquivo'];
        if (!file_exists($filepath)) {
            http_response_code(404);
            echo "Arquivo não encontrado no servidor.";
            exit;
        }

        // Força download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush();
        readfile($filepath);
        exit;

    default:
        echo "Ação inválida.";
        break;
}
