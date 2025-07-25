<?php

require_once __DIR__ . '/../../models/emprestimo_material.php';


$emprestimoMaterial = new EmprestimoMaterial();

$acao = $_GET['acao'] ?? 'listar';
$idemprestimo = $_GET['id'] ?? null;
$emprestimo_para_edicao = null;
$mensagem_sucesso = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // cadastro ou alteração
    if (isset($_POST['submit_cadastro'])) {
        $ok = $emprestimoMaterial->cadastrar(
            $_POST['idaluno'],
            $_POST['idmaterial'],
            $_POST['data_emprestimo'],
            $_POST['data_prevista_devolucao'],
            $_POST['data_devolvido'] ?? null,
            $_POST['status'] ?? 'emprestado',
            $_POST['observacoes'] ?? null,
            $_POST['valor_multa'] ?? 0.00
        );
        if ($ok) {
            $mensagem_sucesso = "Empréstimo cadastrado com sucesso!";
        } else {
            $mensagem_sucesso = "Erro ao cadastrar empréstimo.";
        }
        $acao = 'listar'; // volta para a listagem depois cadastro
    } elseif (isset($_POST['submit_edicao'])) {
        $ok = $emprestimoMaterial->alterar(
            $_POST['idemprestimo'],
            $_POST['idaluno'],
            $_POST['idmaterial'],
            $_POST['data_emprestimo'],
            $_POST['data_prevista_devolucao'],
            $_POST['data_devolvido'] ?? null,
            $_POST['status'],
            $_POST['observacoes'] ?? null,
            $_POST['valor_multa'] ?? 0.00
        );
        if ($ok) {
            $mensagem_sucesso = "Empréstimo alterado com sucesso!";
        } else {
            $mensagem_sucesso = "Erro ao alterar empréstimo.";
        }
        $acao = 'listar'; // volta para a listagem depois da edição
    }
} elseif ($acao === 'excluir' && $idemprestimo) {
    //exvluir
    $ok = $emprestimoMaterial->excluir($idemprestimo);
    if ($ok) {
        $mensagem_sucesso = "Empréstimo excluído com sucesso!";
    } else {
        $mensagem_sucesso = "Erro ao excluir empréstimo.";
    }
    $acao = 'listar'; // volta para a listagem de de excluir
}

// mostrar dados no formualrio
if ($acao === 'editar' && $idemprestimo) {
    $emprestimo_para_edicao = $emprestimoMaterial->listarId($idemprestimo);
    if (!$emprestimo_para_edicao) {
        $mensagem_sucesso = "Empréstimo não encontrado para edição.";
        $acao = 'listar'; // volta para a listagem se nao encontrar
    }
}

$emprestimos = $emprestimoMaterial->listarTodos();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materiais</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<body>
    <div class="container">
        <h1>Gerenciar Empréstimos de Material</h1>

        <?php if (!empty($mensagem_sucesso)): ?>
            <div class="message success"><?php echo htmlspecialchars($mensagem_sucesso); ?></div>
        <?php endif; ?>

        <div class="action-links">
            <a href="?acao=listar">Listar Empréstimos</a>
            <a href="?acao=cadastrar">Cadastrar Novo Empréstimo</a>
        </div>

        <hr>

        <?php if ($acao === 'cadastrar'): ?>
            <h2>Cadastrar Novo Empréstimo</h2>
            <form action="?acao=cadastrar" method="POST">
            <div class="flex">
                <div class="col">
                    <label for="idaluno">ID do Aluno:</label>
                    <input type="number" id="idaluno" name="idaluno" required>
                    <br>
                    <label for="idmaterial">ID do Material:</label>
                    <input type="number" id="idmaterial" name="idmaterial" required>
                    <br>
                    <label for="data_emprestimo">Data do Empréstimo:</label>
                    <input type="date" id="data_emprestimo" name="data_emprestimo" value="<?php echo date('Y-m-d'); ?>" required>
                    <br>
                    <label for="data_prevista_devolucao">Data Prevista de Devolução:</label>
                    <input type="date" id="data_prevista_devolucao" name="data_prevista_devolucao" required>
                    <br>
                    <label for="observacoes">Observações (opcional):</label>
                    <textarea id="observacoes" name="observacoes" rows="3"></textarea>
                    <br>
                    <label for="valor_multa">Valor da Multa (opcional):</label>
                    <input type="number" id="valor_multa" name="valor_multa" step="0.01" value="0.00">
                    <br>
                    <input type="hidden" name="status" value="emprestado">
                    <button type="submit" name="submit_cadastro">Cadastrar Empréstimo</button>
                </div>
            </div>
            </form>

        <?php elseif ($acao === 'editar' && $emprestimo_para_edicao): ?>
            <h2>Editar Empréstimo #<?php echo htmlspecialchars($emprestimo_para_edicao['idemprestimo']); ?></h2>
            <form action="?acao=editar" method="POST">
                <input type="hidden" name="idemprestimo" value="<?php echo htmlspecialchars($emprestimo_para_edicao['idemprestimo']); ?>">
                <br>
                <label for="idaluno">ID do Aluno:</label>
                <input type="number" id="idaluno" name="idaluno" value="<?php echo htmlspecialchars($emprestimo_para_edicao['idaluno']); ?>" required>
                <br>
                <label for="idmaterial">ID do Material:</label>
                <input type="number" id="idmaterial" name="idmaterial" value="<?php echo htmlspecialchars($emprestimo_para_edicao['idmaterial']); ?>" required>
                <br>
                <label for="data_emprestimo">Data do Empréstimo:</label>
                <input type="date" id="data_emprestimo" name="data_emprestimo" value="<?php echo htmlspecialchars($emprestimo_para_edicao['data_emprestimo']); ?>" required>
                <br>
                <label for="data_prevista_devolucao">Data Prevista de Devolução:</label>
                <input type="date" id="data_prevista_devolucao" name="data_prevista_devolucao" value="<?php echo htmlspecialchars($emprestimo_para_edicao['data_prevista_devolucao']); ?>" required>
                <br>
                <label for="data_devolvido">Data Devolvido (opcional):</label>
                <input type="date" id="data_devolvido" name="data_devolvido" value="<?php echo htmlspecialchars($emprestimo_para_edicao['data_devolvido']); ?>">
                <br>
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="emprestado" <?php echo ($emprestimo_para_edicao['status'] == 'emprestado') ? 'selected' : ''; ?>>Emprestado</option>
                    <option value="devolvido" <?php echo ($emprestimo_para_edicao['status'] == 'devolvido') ? 'selected' : ''; ?>>Devolvido</option>
                    <option value="atrasado" <?php echo ($emprestimo_para_edicao['status'] == 'atrasado') ? 'selected' : ''; ?>>Atrasado</option>
                    <option value="cancelado" <?php echo ($emprestimo_para_edicao['status'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                </select>
                <br>
                <label for="observacoes">Observações (opcional):</label>
                <textarea id="observacoes" name="observacoes" rows="3"><?php echo htmlspecialchars($emprestimo_para_edicao['observacoes']); ?></textarea>
                <br>
                <label for="valor_multa">Valor da Multa (opcional):</label>
                <input type="number" id="valor_multa" name="valor_multa" step="0.01" value="<?php echo htmlspecialchars($emprestimo_para_edicao['valor_multa']); ?>">
                <br>
                <button type="submit" name="submit_edicao">Salvar Alterações</button>
            </form>

        <?php else: ?>
            <h2>Lista de Empréstimos</h2>
            <div class="table-responsive custom-table"></div>
            <table class="table table-striped table-hover align-middle">
                <thead class="table-header">
                    <tr>
                        <th>ID Empréstimo</th>
                        <th>ID Aluno</th>
                        <th>ID Material</th>
                        <th>Data Empréstimo</th>
                        <th>Devolução Prevista</th>
                        <th>Data Devolvido</th>
                        <th>Status</th>
                        <th>Valor Multa</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($emprestimos)): ?>
                        <?php foreach ($emprestimos as $emprestimo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($emprestimo['idemprestimo']); ?></td>
                                <td><?php echo htmlspecialchars($emprestimo['idaluno']); ?></td>
                                <td><?php echo htmlspecialchars($emprestimo['idmaterial']); ?></td>
                                <td><?php echo htmlspecialchars($emprestimo['data_emprestimo']); ?></td>
                                <td><?php echo htmlspecialchars($emprestimo['data_prevista_devolucao']); ?></td>
                                <td><?php echo ($emprestimo['data_devolvido'] ? htmlspecialchars($emprestimo['data_devolvido']) : 'Pendente'); ?></td>
                                <td><?php echo htmlspecialchars($emprestimo['status']); ?></td>
                                <td>R$ <?php echo number_format($emprestimo['valor_multa'], 2, ',', '.'); ?></td>
                                <td class="text-end">
                                    <a href="?acao=editar&id=<?php echo htmlspecialchars($emprestimo['idemprestimo']); ?>"><i class="fas fa-edit"></i> Editar</a></a>
                                    <a href="?acao=excluir&id=<?php echo htmlspecialchars($emprestimo['idemprestimo']); ?>" class="inline-delete-btn"
                                onclick="return confirm('Tem certeza que deseja excluir este funcionário?');">
                                <i class="fas fa-trash-alt"></i> Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan='9'>Nenhum empréstimo encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
<script src="../../../public/js/admin_script.js"></script>
</body>
</html>