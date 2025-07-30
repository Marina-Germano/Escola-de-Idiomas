<?php

session_start();

// Verifica se o usuário está logado e se possui o papel de 'aluno' ou 'admin'.
if (!isset($_SESSION['idusuario'])) {
    error_log("DEBUG: Redirecionando para login. Usuário não logado ou papel incorreto.");
    header('Location: /escola-de-idiomas/conexus_sistema/app/views/login.php');
    exit();
}

// Pega o ID do usuário e o papel da sessão.
$idusuarioLogado = $_SESSION['idusuario'];
$papelUsuarioLogado = $_SESSION['papel'];
error_log("DEBUG: idusuario logado: " . $idusuarioLogado . ", Papel: " . $papelUsuarioLogado);

// Inclui os arquivos de conexão com o banco de dados e o modelo de avaliação.
require_once __DIR__ . "/../config/conexao.php"; 
require_once __DIR__ . "/../models/avaliacao.php"; 
require_once __DIR__ . "/../models/turma.php"; 
require_once __DIR__ . "/../models/aluno_turma.php";

// Cria uma instância do modelo Avaliacao.
$avaliacaoModel = new Avaliacao();
$turmaModel = new Turma();
$alunoTurmaModel = new AlunoTurma();

// Função para verificar permissão de manipulação (CRUD).
function temPermissaoManipulacao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['admin', 'funcionario', 'professor']);
}

// Lógica para buscar os cursos do aluno logado (SIMULAÇÃO).
// Em uma aplicação real, você buscaria isso do banco de dados através de um modelo apropriado (ex: CursoModel).
$cursosDoAluno = [];
$mapIdToNomeCurso = []; // Mapa para armazenar id_curso para nome_curso
if ($papelUsuarioLogado === 'aluno' || $papelUsuarioLogado === 'admin') { // Assume que admins também podem ver todos os cursos simulados
    // A simulação anterior era para idusuario == 1, mantendo a consistência.
    // Em um cenário real, você buscaria os cursos específicos do $idusuarioLogado.
    $cursosDoAluno = [
        ['id_curso' => 'ingles', 'nome_curso' => 'Inglês'],
        // Exemplo: ['id_curso' => 'espanhol', 'nome_curso' => 'Espanhol'],
    ];
    // Preenche o mapa para facilitar a busca do nome do curso pelo ID.
    foreach ($cursosDoAluno as $curso) {
        $mapIdToNomeCurso[$curso['id_curso']] = $curso['nome_curso'];
    }
}
// FIM DA SIMULAÇÃO DE BUSCA DE CURSOS

// Lógica principal do controlador: decide o que fazer com base na requisição.
// Se não houver uma 'acao' específica na URL, significa que é uma requisição para carregar a página do boletim.
if (!isset($_GET['acao'])) {
    // Verifica se o usuário é um aluno ou admin para visualizar o próprio boletim.
    if ($papelUsuarioLogado === 'aluno' || $papelUsuarioLogado === 'admin') {
        // Pega o curso selecionado pelo formulário POST ou da URL GET.
        $cursoSelecionadoId = ''; 
        if (isset($_POST['curso']) && $_POST['curso'] !== 'default') {
            $cursoSelecionadoId = strtolower($_POST['curso']); 
        } else if (isset($_GET['curso']) && $_GET['curso'] !== 'default') {
            $cursoSelecionadoId = strtolower($_GET['curso']);
        }
        
        $idiomaParaModelo = null; // Armazenará a descrição do idioma (ex: 'Inglês').
        if ($cursoSelecionadoId !== 'default' && $cursoSelecionadoId !== '') {
            // Usa o mapa para obter o nome completo do idioma/curso com base no ID.
            $idiomaParaModelo = $mapIdToNomeCurso[$cursoSelecionadoId] ?? null;
        }

        // Chama o método do Modelo para buscar as avaliações do aluno, filtrando por idioma se houver.
        $atividades = []; // Inicializa para garantir que sempre seja um array.
        try {
            $atividades = $avaliacaoModel->getAvaliacoesByAlunoAndIdioma($idusuarioLogado, $idiomaParaModelo);
            error_log("DEBUG: getAvaliacoesByAlunoAndIdioma executado com sucesso. " . count($atividades) . " atividades encontradas.");
        } catch (PDOException $e) {
            error_log("ERRO PDO em avaliacaoController ao buscar avaliações: " . $e->getMessage());
            // Em caso de erro, pode-se decidir exibir uma mensagem ou redirecionar.
            // Por enquanto, apenas loga e a view exibirá "Nenhuma atividade encontrada".
        } catch (Exception $e) {
            error_log("ERRO GERAL em avaliacaoController ao buscar avaliações: " . $e->getMessage());
        }

        // Mantém $cursoSelecionado com o ID do curso para que o <select> na view mantenha a seleção.
        $cursoSelecionado = $cursoSelecionadoId; 

        // O cabeçalho foi removido daqui, pois a view boletim.php não o inclui mais.
        include __DIR__ . '/../views/student/notes.php';
        exit; 
    } else {
        // Outros papéis (funcionário/professor) não deveriam acessar diretamente o boletim do aluno assim.
        http_response_code(403);
        echo "Acesso negado. Usuário sem permissão para visualizar boletins diretamente.";
        exit;
    }
}

// Se houver uma 'acao' na URL, processa as operações de CRUD.
// CORREÇÃO: Usa o operador de coalescência nula para garantir que $acao sempre tenha um valor.
$acao = $_GET['acao'] ?? ''; // Linha corrigida

switch ($acao) {
    case 'cadastrar':
        if (!temPermissaoManipulacao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem cadastrar avaliações.";
            exit;
        }

        // Validação e sanitização dos inputs (IMPORTANTE! Adicione conforme a necessidade)
        $idaluno_turma = $_POST['idaluno_turma'] ?? null;
        $idfuncionario = $_SESSION['idfuncionario'] ?? null; // Assume que o id do funcionário está na sessão
        $idturma = $_POST['idturma'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $titulo = $_POST['titulo'] ?? null;
        $data_avaliacao = $_POST['data_avaliacao'] ?? null;
        $nota = $_POST['nota'] ?? null;
        $peso = $_POST['peso'] ?? 1.0;
        $observacao = $_POST['observacao'] ?? null;

        // Validação básica dos dados obrigatórios
        if (!$idaluno_turma || !$idfuncionario || !$descricao || !$titulo || !$data_avaliacao || !isset($nota)) {
            http_response_code(400);
            echo "Dados insuficientes para cadastrar avaliação.";
            exit;
        }

        $ok = $avaliacaoModel->cadastrar(
            $idaluno_turma,
            $idfuncionario,
            $idturma,
            $descricao,
            $titulo,
            $data_avaliacao,
            $nota,
            $peso,
            $observacao
        );

        if ($ok) {
            // --- ALTERAÇÃO SUGERIDA ---
            header('Location: ../views/teacher/list_test.php?mensagem=Avaliação cadastrada com sucesso!');
            // --- FIM DA ALTERAÇÃO SUGERIDA ---
        } else {
            // --- ALTERAÇÃO SUGERIDA ---
            header('Location: ../views/teacher/list_test.php?mensagem=Erro ao cadastrar avaliação!');
            // --- FIM DA ALTERAÇÃO SUGERIDA ---
        }
        exit(); // Garante que o script pare após o redirecionamento
        break;

    case 'alterar':
        if (!temPermissaoManipulacao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem alterar avaliações.";
            exit;
        }

        // Validação e sanitização (adicione mais, se necessário)
        $idavaliacao = $_POST['idavaliacao'] ?? null;
        $idaluno_turma = $_POST['idaluno_turma'] ?? null;
        $idturma = $_POST['idturma'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $titulo = $_POST['titulo'] ?? null;
        $data_avaliacao = $_POST['data_avaliacao'] ?? null;
        $nota = $_POST['nota'] ?? null;
        $peso = $_POST['peso'] ?? 1.0;
        $observacao = $_POST['observacao'] ?? null;
        
        if (!$idavaliacao || !$idaluno_turma || !$descricao || !$titulo || !$data_avaliacao || !isset($nota)) {
            http_response_code(400);
            echo "Dados insuficientes para alterar avaliação.";
            exit;
        }

        $ok = $avaliacaoModel->alterar(
            $idavaliacao,
            $idaluno_turma,
            $idturma,
            $descricao,
            $titulo,
            $data_avaliacao,
            $nota,
            $peso,
            $observacao
        );

        if ($ok) {
            // --- ALTERAÇÃO SUGERIDA ---
            header('Location: ../views/teacher/list_test.php?mensagem=Avaliação alterada com sucesso!');
            // --- FIM DA ALTERAÇÃO SUGERIDA ---
        } else {
            // --- ALTERAÇÃO SUGERIDA ---
            header('Location: ../views/teacher/list_test.php?mensagem=Erro ao alterar avaliação!');
            // --- FIM DA ALTERAÇÃO SUGERIDA ---
        }
        exit(); // Garante que o script pare após o redirecionamento
        break;
    case 'excluir':
        if (!temPermissaoManipulacao()) {
            http_response_code(403);
            header('Location: ../views/teacher/list_teste.php');
                exit;
        }

        if (!isset($_GET['idavaliacao'])) {
            http_response_code(400);
            echo "ID da avaliação não informado.";
            exit;
        }

        $ok = $avaliacaoModel->excluir($_GET['idavaliacao']);
        if ($ok) {
            // --- ALTERAÇÃO SUGERIDA ---
            header('Location: ../views/teacher/list_test.php?mensagem=Avaliação excluída com sucesso!');
            // --- FIM DA ALTERAÇÃO SUGERIDA ---
        } else {
            // --- ALTERAÇÃO SUGERIDA ---
            header('Location: ../views/teacher/list_test.php?mensagem=Erro ao excluir avaliação!');
            // --- FIM DA ALTERAÇÃO SUGERIDA ---
        }
        exit(); // Garante que o script pare após o redirecionamento
        break;

    case 'listarTodos':
        header('Content-Type: application/json');
        echo json_encode($avaliacaoModel->listarTodos());
        break;

    case 'listarId':
        if (!isset($_GET['idavaliacao'])) {
            http_response_code(400);
            echo "ID da avaliação não informado.";
            exit;
        }
        header('Content-Type: application/json');
        echo json_encode($avaliacaoModel->listarId($_GET['idavaliacao']));
        break;
    
    case 'registrar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idavaliacao = $_POST['idavaliacao'];
            $peso = $_POST['peso'];
            $nota = $_POST['nota'] ?? []; // array com idaluno_turma dos faltantes

            $avaliacaoModel->listarTodos($idavaliacao, $peso, $nota);

            header('Location: ../views/teacher/list_class.php?mensagem=Presenças registradas');
            exit; 
        }
        break;
    
    case 'listarAlunos':
        if (!temPermissaoManipulacao()) {
            http_response_code(403);
            echo "Apenas usuários autorizados podem listar alunos para chamada.";
            exit;
        }

        $idturma = $_GET['idturma'] ?? null;
        if ($idturma) {
            $alunos = $alunoTurmaModel->listarTodos($idturma); // Método hipotético no AlunoTurmaModel
            if ($alunos === false) { // Supondo que o método retorne false em caso de erro
                error_log("ERRO: Falha ao listar alunos para a turma " . $idturma);
                $alunos = []; // Garante que $alunos seja um array vazio para a view
                echo "Erro ao buscar alunos da turma.";
            }
            include __DIR__ . '/../views/teacher/test_score.php';
        } else {
            http_response_code(400);
            echo "Turma não especificada para chamada.";
        }
        break;

    default:
        http_response_code(400); // Bad Request
        echo "Ação inválida ou não suportada.";
        break;
}
