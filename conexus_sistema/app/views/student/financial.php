<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Financeiro - Conexus</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="/escola-de-idiomas/conexus_sistema/public/css/style.css">
</head>
<body>

  <?php include __DIR__ . '/../components/student_header.php'; ?>

  <section class="courses">
    <h1 class="heading">Minhas Atividades Financeiras</h1>

    <div class="row">
      <form method="post">
        <label for="curso" class="heading">Escolha um curso para ver os detalhes financeiros:</label>
        <select name="curso" id="curso" class="select-course" required>
          <option value="default">-- Selecione um Curso --</option>
          <?php foreach ($cursosDoAluno as $curso): ?>
            <option value="<?= htmlspecialchars($curso['id_curso']) ?>"
              <?= $cursoSelecionado === $curso['id_curso'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($curso['nome_curso']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="inline-btn">Ver</button>
      </form>
    </div>
  </section>

  <?php if (!empty($erroFinanceiro)): ?>
    <div class="message error-message">
      <span><?= htmlspecialchars($erroFinanceiro) ?></span>
    </div>
  <?php endif; ?>

  <?php if (!empty($cursoSelecionado) && $cursoSelecionado !== 'default'): ?>
  <section class="financial-section">
    <h2 class="heading">Detalhes Financeiros para <?= ucfirst($cursoSelecionado) ?></h2>

    <div class="card-row">
      <div class="card success-card">
        <i class="fas fa-check-circle icon-success"></i>
        <h3>Total Pago</h3>
        <p class="amount success-amount">R$ <?= number_format($totalPago, 2, ',', '.') ?></p>
      </div>
      <div class="card danger-card">
        <i class="fas fa-exclamation-triangle icon-danger"></i>
        <h3>Total Pendente</h3>
        <p class="amount danger-amount">R$ <?= number_format($totalPendente, 2, ',', '.') ?></p>
      </div>
    </div>

    <h3 class="heading">Histórico de Pagamentos</h3>

    <?php if (!empty($historicoPagamentos)): ?>
      <div class="table-container">
        <table class="styled-table">
          <thead>
            <tr>
              <th>Data</th>
              <th>Descrição</th>
              <th>Valor</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($historicoPagamentos as $pagamento): ?>
              <tr>
                <td>
                  <?php
                  if ($pagamento['status'] === 'pago' && !empty($pagamento['data_pagamento'])) {
                    echo htmlspecialchars($pagamento['data_pagamento']);
                  } elseif ($pagamento['status'] === 'pendente' && !empty($pagamento['data_vencimento'])) {
                    echo htmlspecialchars($pagamento['data_vencimento']);
                  } else {
                    echo 'N/A';
                  }
                  ?>
                </td>
                <td><?= htmlspecialchars($pagamento['descricao']) ?></td>
                <td>R$ <?= number_format($pagamento['valor'], 2, ',', '.') ?></td>
                <td class="<?= $pagamento['status'] === 'pago' ? 'text-success' : 'text-danger' ?>">
                  <?= htmlspecialchars($pagamento['status']) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="no-data">Nenhum pagamento registrado para este curso ainda.</p>
    <?php endif; ?>

    <h3 class="heading">Pagamentos Pendentes</h3>
    <?php if (!empty($pagamentosPendentes)): ?>
      <div class="table-container">
        <table class="styled-table">
          <thead>
            <tr>
              <th>Vencimento</th>
              <th>Descrição</th>
              <th>Valor</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pagamentosPendentes as $pendente): ?>
              <tr>
                <td><?= htmlspecialchars($pendente['vencimento']) ?></td>
                <td><?= htmlspecialchars($pendente['descricao']) ?></td>
                <td>R$ <?= number_format($pendente['valor'], 2, ',', '.') ?></td>
                <td><button class="inline-btn btn-primary">Pagar</button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="no-data">Parabéns! Nenhuma pendência financeira para este curso.</p>
    <?php endif; ?>
  </section>
  <?php endif; ?>

  <script src="/escola-de-idiomas/conexus_sistema/public/js/script.js"></script>
</body>
</html>
