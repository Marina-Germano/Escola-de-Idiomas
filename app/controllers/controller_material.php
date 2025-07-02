<?php
session_start();
require_once "../model/Material.php";

$material = new Material();

// Função para verificar se o usuário está logado como professor ou admin
function verificarPermissaoProfessorOuAdmin() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['professor', 'admin']);
}

// Função para obter o ID do professor logado (caso exista)
function idProfessorLogado() {
    return $_SESSION['idprofessor'] ?? null;
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    switch ($acao) {
        case 'cadastrar':
            if (!verificarPermissaoProfessorOuAdmin()) {
                http_response_code(403);
                echo "Acesso negado. Apenas professores ou administradores podem cadastrar materiais.";
                exit;
            }

            $material->cadastrar(
                $_POST['idtipo_material'],
                $_POST['ididioma'],
                $_POST['idnivel'],
                $_POST['titulo'],
                $_POST['descricao'],
                $_POST['quantidade'],
                $_POST['formato_arquivo'],
                $_POST['link_download'],
                idProfessorLogado()
            );
            echo "Material cadastrado com sucesso!";
            break;

        case 'alterar':
            if (!verificarPermissaoProfessorOuAdmin()) {
                http_response_code(403);
                echo "Acesso negado. Apenas professores ou administradores podem alterar materiais.";
                exit;
            }

            $material->alterar(
                $_POST['idmaterial'],
                $_POST['idtipo_material'],
                $_POST['ididioma'],
                $_POST['idnivel'],
                $_POST['titulo'],
                $_POST['descricao'],
                $_POST['quantidade'],
                $_POST['formato_arquivo'],
                $_POST['link_download'],
                idProfessorLogado()
            );
            echo "Material alterado com sucesso!";
            break;

        case 'excluir':
            if (!verificarPermissaoProfessorOuAdmin()) {
                http_response_code(403);
                echo "Acesso negado. Apenas professores ou administradores podem excluir materiais.";
                exit;
            }

            $material->excluir($_GET['idmaterial']);
            echo "Material excluído com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($material->listarTodos());
            break;

        case 'listarId':
            echo json_encode($material->listarId($_GET['idmaterial']));
            break;

        case 'listarPorProfessor':
            if (!verificarPermissaoProfessorOuAdmin()) {
                http_response_code(403);
                echo "Acesso negado.";
                exit;
            }

            echo json_encode($material->listarPorProfessor(idProfessorLogado()));
            break;

        case 'buscarMateriais':
            $ididioma = $_GET['ididioma'];
            $idnivel = $_GET['idnivel'] ?? null;
            $idtipo_material = $_GET['idtipo_material'] ?? null;

            echo json_encode($material->buscarMateriais($ididioma, $idnivel, $idtipo_material));
            break;

        default:
            echo "Ação inválida.";
    }
} else {
    echo "Nenhuma ação definida.";
}
