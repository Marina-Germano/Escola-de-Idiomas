<?php
session_start();
require_once __DIR__ . '/../models/presenca.php';

$presenca = new Presenca();
$acao = $_GET['acao'] ?? '';


switch ($acao) {
    case 'registrar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idfuncionario = $_SESSION['idusuario'] ?? null;
            $idturma = $_POST['idturma'] ?? null;
            $faltas = $_POST['faltas'] ?? []; // array com idaluno_turma dos faltantes

            if ($idfuncionario && $idturma) {
                require_once __DIR__ . '/../models/aluno_turma.php';
                $alunoTurmaModel = new AlunoTurma();
                $alunos = $alunoTurmaModel->listarTodos($idturma);

                foreach ($alunos as $aluno) {
                    $idaluno_turma = $aluno['idaluno_turma'];
                    $presente = in_array($idaluno_turma, $faltas) ? 0 : 1;
                    $presenca->registrarPresenca($idaluno_turma, $idfuncionario, $presente);
                }

                header('Location: ../views/teacher/list_class.php?mensagem=Presenças registradas');
                exit;
            } else {
                echo "Dados incompletos para registrar presença.";
            }
        }
        break;

    case 'alterar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idpresenca = $_POST['idpresenca'] ?? null;
            $presente = isset($_POST['presente']) ? 1 : 0;

            if ($idpresenca) {
                $presenca->alterar($idpresenca, $presente);
                header('Location: ../views/teacher/class.php?mensagem=Presença alterada');
                exit;
            } else {
                echo "ID da presença não informado.";
            }
        }
        break;

    case 'excluir':
        $idpresenca = $_GET['idpresenca'] ?? null;
        if ($idpresenca) {
            $presenca->excluir($idpresenca);
            header('Location: ../views/teacher/list_class.php?mensagem=Presença excluída');
            exit;
        } else {
            echo "ID da presença não informado para exclusão.";
        }
        break;

    case 'listarTurma':
        $idturma = $_GET['idturma'] ?? null;
        if ($idturma) {
            $lista = $presenca->listarPresencasPorTurma($idturma);
            include '../views/teacher/list_class.php';
        } else {
            echo "Turma não especificada.";
        }
        break;

    case 'chamada':
        $idturma = $_GET['idturma'] ?? null;
        if ($idturma) {
            require_once __DIR__ . '/../models/aluno_turma.php';
            $alunoTurmaModel = new AlunoTurma();
            $alunos = $alunoTurmaModel->listarTodos($idturma);
            include '../views/teacher/list_students.php';
        } else {
            echo "Turma não especificada para chamada.";
        }
        break;

    case 'listar_todos':
    default:
        $lista = $presenca->listarTodos();
        include '../views/teacher/list_students.php';
        break;

    case 'listarAlunos':
    $idturma = $_GET['idturma'] ?? null;
    if ($idturma) {
        require_once __DIR__ . '/../models/aluno_turma.php';
        $alunoTurmaModel = new AlunoTurma();
        $alunos = $alunoTurmaModel->listarTodos($idturma);
        include '../views/teacher/list_students.php';
    } else {
        echo "Turma não especificada para chamada.";
    }
    break;

}