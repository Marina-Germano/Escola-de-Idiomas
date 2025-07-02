<?php
session_start();
require_once "../model/EmprestimoMaterial.php";

$emprestimo = new EmprestimoMaterial();

// Função simples para verificar se o usuário está logado (exemplo básico)
function estaLogado() {
    return isset($_SESSION['idusuario']);
}

// Aqui você pode adaptar a permissão para quem pode cadastrar/alterar/excluir,
// por exemplo, só funcionários ou administradores podem realizar essas ações.
function temPermissao() {
    return isset($_SESSION['papel']) && in_array($_SESSION['papel'], ['funcionario', 'admin']);
}

if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];

    switch ($acao) {
        case 'cadastrar':
            if (!estaLogado() || !temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas funcionários ou administradores podem cadastrar empréstimos.";
                exit;
            }

            $emprestimo->cadastrar(
                $_POST['idaluno'],
                $_POST['idmaterial'],
                $_POST['data_emprestimo'],
                $_POST['data_prevista_devolucao'],
                $_POST['data_devolvido'],
                $_POST['status'] ?? 'Disponível',
                $_POST['observacoes'] ?? null,
                $_POST['valor_multa'] ?? 0.00
            );
            echo "Empréstimo cadastrado com sucesso!";
            break;

        case 'alterar':
            if (!estaLogado() || !temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas funcionários ou administradores podem alterar empréstimos.";
                exit;
            }

            $emprestimo->alterar(
                $_POST['idemprestimo'],
                $_POST['idaluno'],
                $_POST['idmaterial'],
                $_POST['data_emprestimo'],
                $_POST['data_prevista_devolucao'],
                $_POST['data_devolvido'],
                $_POST['status'],
                $_POST['observacoes'] ?? null,
                $_POST['valor_multa'] ?? 0.00
            );
            echo "Empréstimo alterado com sucesso!";
            break;

        case 'excluir':
            if (!estaLogado() || !temPermissao()) {
                http_response_code(403);
                echo "Acesso negado. Apenas funcionários ou administradores podem excluir empréstimos.";
                exit;
            }

            $emprestimo->excluir($_GET['idemprestimo']);
            echo "Empréstimo excluído com sucesso!";
            break;

        case 'listarTodos':
            echo json_encode($emprestimo->listarTodos());
            break;

        case 'listarId':
            echo json_encode($emprestimo->listarId($_GET['idemprestimo']));
            break;

        default:
            echo "Ação inválida.";
            break;
    }
} else {
    echo "Nenhuma ação definida.";
}
