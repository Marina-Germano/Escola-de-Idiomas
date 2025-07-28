<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../models/aluno.php';
require_once "../../models/turma.php";
require_once "../../models/aluno_turma.php";


// Pega o idturma enviado por GET
$idturma = $_GET['idturma'] ?? null;

  if (!$idturma) {
      echo "ID da turma não informado.";
      exit;
  }

$alunoModel = new Aluno();
// Busca os alunos não vinculados a essa turma
$itens = $alunoModel->listarNaoVinculados($idturma);

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro de Aluno na Turma</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
  <link rel="stylesheet" href="../../../public/css/admin_style.css" />
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="box-container-list">
    <div class="flex-between heading-bar">
        <h1 class="heading">Estudantes</h1>
        <a href="list_class.php" class="inline-option-btn"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
    </div>

    <?php if (!empty($itens)): ?>
        <div class="table-responsive custom-table">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-header">
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Situação</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td><?= htmlspecialchars($item['cpf']) ?></td>
                            <td><?= htmlspecialchars($item['situacao'] ?? 'sem situacao') ?></td>
                            <td class="text-end">
                                <a href="../../controllers/alunoTurmaController.php?acao=vincular&idturma=<?=$idturma?>&idaluno=<?=$item['idaluno'] ?>"
                                class="inline-btn">
                                    <i class="fa-solid fa-user-plus"></i> Adicionar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty">Nenhum estudante cadastrado.</div>
    <?php endif; ?>
</div>

<script src="../../../public/js/admin_script.js"></script>

</body>
</html>

