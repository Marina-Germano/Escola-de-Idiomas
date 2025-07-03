<?php
session_start();
require_once "../model/CalendarioAula.php";

$calendario = new CalendarioAula();

function estaLogado() {
    return isset($_SESSION['idusuario']);
}

function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['professor', 'admin', 'funcionario']); //mantem o professor aqui?
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
        case 'alterar':
        case 'excluir':
            if (!temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas usuários autorizados podem executar essa ação.";
                exit;
            }
            if ($acao == 'cadastrar') {
                $calendario->cadastrar(
                    $_POST['data_aula'],
                    $_POST['hora_inicio'],
                    $_POST['hora_fim'],
                    $_POST['idprofessor'],
                    $_POST['idturma'],
                    $_POST['idmaterial'],
                    $_POST['sala'] ?? null,
                    $_POST['observacoes'] ?? null,
                    $_POST['status_aula'] ?? "Agendada",
                    $_POST['link_reuniao'] ?? null,
                    isset($_POST['aula_extra']) ? (bool)$_POST['aula_extra'] : false,
                    isset($_POST['recorrente']) ? (bool)$_POST['recorrente'] : false
                );
                echo "Aula cadastrada com sucesso!";
                
            } 
            elseif ($acao == 'alterar') {
                $calendario->alterar(
                    $_POST['idaula'],
                    $_POST['data_aula'],
                    $_POST['hora_inicio'],
                    $_POST['hora_fim'],
                    $_POST['idprofessor'],
                    $_POST['idturma'],
                    $_POST['idmaterial'],
                    $_POST['sala'] ?? null,
                    $_POST['observacoes'] ?? null,
                    $_POST['status_aula'] ?? "Agendada",
                    $_POST['link_reuniao'] ?? null,
                    isset($_POST['aula_extra']) ? (bool)$_POST['aula_extra'] : false,
                    isset($_POST['recorrente']) ? (bool)$_POST['recorrente'] : false
                );
                echo "Aula alterada com sucesso!";
            } 
            elseif ($acao == 'excluir') {
                $calendario->excluir($_GET['idaula']);
                echo "Aula excluída com sucesso!";
            }
            break;

        case 'listarTodos':
            // Permite listar para qualquer usuário logado, inclusive alunos
            echo json_encode($calendario->listarTodos());
            break;

        case 'listarId':
            echo json_encode($calendario->listarId($_GET['idaula']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
