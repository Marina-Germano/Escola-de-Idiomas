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

<!-- Header -->
<header class="header">
  <section class="flex">
    <a href="dashboard.php" class="logo">
      <img src="../../../public/img/conexus_sem_fundo.png" alt="Logo Conexus"> Conexus
    </a>

    <form action="search_page.php" method="post" class="search-form">
      <input type="text" name="search" placeholder="Buscar..." required maxlength="100">
      <button type="submit" class="fas fa-search" name="search_btn"></button>
    </form>

    <div class="icons">
      <div id="menu-btn" class="fas fa-bars"></div>
      <div id="search-btn" class="fas fa-search"></div>
      <div id="user-btn" class="fas fa-user"></div>
      <div id="toggle-btn" class="fas fa-sun"></div>
    </div>

    <div class="profile">
      <?php if ($fetch_profile): ?>
        <img src="../../../public/img/pic-1.jpg" alt="Foto do usuário">
        <h3><?= htmlspecialchars($fetch_profile['nome']) ?></h3>
        <span><?= htmlspecialchars($fetch_profile['papel']) ?></span>
      <?php else: ?>
        <p>Usuário não identificado</p>
      <?php endif; ?>
    </div>
  </section>
</header>

<!-- Sidebar -->
<div class="side-bar">
  <div class="close-side-bar">
    <i class="fas fa-times"></i>
  </div>
  <nav class="navbar">
    <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
    <a href="list_class.php"><i class="fa-solid fa-bars-staggered"></i><span>Listar Turmas</span></a>
    <a href="list_students.php"><i class="fas fa-graduation-cap"></i><span>Listar Estudantes</span></a>
    <a href="list_employer.php"><i class="fas fa-users"></i><span>Listar Funcionários</span></a>
    <a href="list_material.php"><i class="fas fa-book"></i><span>Listar Materiais</span></a>
    <a href="material_loan.php"><i class="fas fa-handshake"></i><span>Gerenciar Empréstimos</span></a>
    <a href="../components/admin_logout.php" onclick="return confirm('Deseja sair?');">
      <i class="fas fa-right-from-bracket"></i><span>Sair</span>
    </a>
  </nav>
</div>
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
                                <a href="student_class.html" class="inline-btn">
                                    <i class="fa-solid fa-user-plus"></i> Adicionar
                                </a>
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

