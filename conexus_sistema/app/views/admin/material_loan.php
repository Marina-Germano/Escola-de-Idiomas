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
    <title>Emprestimos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<body>
    <div class="box-container-list">
        <div class="flex-between heading-bar">
            <h1 class="heading">Gerenciar Empréstimos de Material</h1>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="message success"><?php echo htmlspecialchars($mensagem_sucesso); ?></div>
            <?php endif; ?>
            <div class="action-links">
                <a href="?acao=listar" class="inline-btn">Listar Empréstimos</a>
                <a href="?acao=cadastrar" class="inline-btn">Cadastrar Novo Empréstimo</a>
            </div>
        </div>
        <?php if ($acao === 'cadastrar'): ?>
        <section class="box-container-list">
            <div class="flex-between heading-bar">
                <h1 class="heading">Novo Empréstimo</h1>
            </div>

            <form action="?acao=cadastrar" method="POST" class="form-box">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="idaluno"><strong>ID do Aluno <span>*</span></strong></label>
                        <input type="number" id="idaluno" name="idaluno" required class="box" placeholder="Digite o ID do aluno">
                    </div>

                    <div class="form-group">
                        <label for="idmaterial"><strong>ID do Material <span>*</span></strong></label>
                        <input type="number" id="idmaterial" name="idmaterial" required class="box" placeholder="Digite o ID do material">
                    </div>

                    <div class="form-group">
                        <label for="data_emprestimo"><strong>Data do Empréstimo <span>*</span></strong></label>
                        <input type="date" id="data_emprestimo" name="data_emprestimo" value="<?= date('Y-m-d'); ?>" required class="box">
                    </div>

                    <div class="form-group">
                        <label for="data_prevista_devolucao"><strong>Data Prevista de Devolução <span>*</span></strong></label>
                        <input type="date" id="data_prevista_devolucao" name="data_prevista_devolucao" required class="box">
                    </div>

                    <div class="form-group full-span">
                        <label for="observacoes"><strong>Observações</strong></label>
                        <textarea id="observacoes" name="observacoes" rows="3" class="box" placeholder="Observações sobre o empréstimo (opcional)"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="valor_multa"><strong>Valor da Multa</strong></label>
                        <input type="number" id="valor_multa" name="valor_multa" step="0.01" value="0.00" class="box" placeholder="0.00">
                    </div>

                    <input type="hidden" name="status" value="emprestado">
                </div>

                <div class="form-actions">
                    <input type="submit" name="submit_cadastro" value="Cadastrar Empréstimo" class="btn">
                </div>
            </form>
        </section>
        
        <?php elseif ($acao === 'editar' && $emprestimo_para_edicao): ?>
        <section class="box-container-list">
            <form action="?acao=editar" method="POST" class="form-box">
                <input type="hidden" name="idemprestimo" value="<?= htmlspecialchars($emprestimo_para_edicao['idemprestimo']) ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="idaluno">ID do Aluno <span>*</span></label>
                        <input type="number" id="idaluno" name="idaluno" required class="box"
                            value="<?= htmlspecialchars($emprestimo_para_edicao['idaluno']) ?>"
                            placeholder="Digite o ID do aluno">
                    </div>

                    <div class="form-group">
                        <label for="idmaterial">ID do Material <span>*</span></label>
                        <input type="number" id="idmaterial" name="idmaterial" required class="box"
                            value="<?= htmlspecialchars($emprestimo_para_edicao['idmaterial']) ?>"
                            placeholder="Digite o ID do material">
                    </div>

                    <div class="form-group">
                        <label for="data_emprestimo">Data do Empréstimo <span>*</span></label>
                        <input type="date" id="data_emprestimo" name="data_emprestimo" required class="box"
                            value="<?= htmlspecialchars($emprestimo_para_edicao['data_emprestimo']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="data_prevista_devolucao">Data Prevista de Devolução <span>*</span></label>
                        <input type="date" id="data_prevista_devolucao" name="data_prevista_devolucao" required class="box"
                            value="<?= htmlspecialchars($emprestimo_para_edicao['data_prevista_devolucao']) ?>">
                    </div>

                    <div class="form-group full-span">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" rows="3" class="box"
                            placeholder="Observações sobre o empréstimo (opcional)"><?= htmlspecialchars($emprestimo_para_edicao['observacoes']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="valor_multa">Valor da Multa</label>
                        <input type="number" id="valor_multa" name="valor_multa" step="0.01" class="box"
                            value="<?= htmlspecialchars($emprestimo_para_edicao['valor_multa']) ?>"
                            placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="data_devolvido">Data Devolvido</label>
                        <input type="date" id="data_devolvido" name="data_devolvido" class="box"
                            value="<?= htmlspecialchars($emprestimo_para_edicao['data_devolvido']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="status">Status <span>*</span></label>
                        <select id="status" name="status" required class="box">
                            <option value="emprestado" <?= $emprestimo_para_edicao['status'] == 'emprestado' ? 'selected' : '' ?>>Emprestado</option>
                            <option value="devolvido" <?= $emprestimo_para_edicao['status'] == 'devolvido' ? 'selected' : '' ?>>Devolvido</option>
                            <option value="atrasado" <?= $emprestimo_para_edicao['status'] == 'atrasado' ? 'selected' : '' ?>>Atrasado</option>
                            <option value="cancelado" <?= $emprestimo_para_edicao['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                    </div>
                </div>
                <input type="submit" name="submit_edicao" value="Salvar Alterações" class="btn">
            </form>
        </section>
        <?php else: ?>
            <div class="table-responsive custom-table">
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
                                    <td class="text-end" style="display: flex;">
                                        <a href="?acao=editar&id=<?php echo htmlspecialchars($emprestimo['idemprestimo']); ?>" class="inline-option-btn"><i class="fas fa-edit"></i> Editar</a></a>
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
        </div>
        <?php endif; ?>
    </div>
<script src="../../../public/js/admin_script.js"></script>
</body>
</html>

