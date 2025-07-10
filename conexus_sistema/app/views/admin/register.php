<?php
session_start();
// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['papel']) || $_SESSION['papel'] !== 'admin') {
   echo "Acesso negado. Apenas administradores podem acessar esta página.";
   exit;
}

// Initialize form variables to retain values after submission or if an error occurs
$nome = $_POST['nome'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$data_nascimento = $_POST['data_nascimento'] ?? '';
$especialidade = $_POST['especialidade'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Conexus - Cadastro de Professor</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
   <link rel="stylesheet" href="/public/css/admin_style.css" />
</head>
<body style="padding-left: 0;">

<header class="header">
<section class="flex">
   <a href="dashboard.php" class="logo">Admin.</a>

   <form action="search_page.php" method="post" class="search-form">
      <input type="text" name="search" placeholder="search here..." required maxlength="100" />
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>

   <div class="icons">
      <div id="menu-btn" class="fas fa-bars"></div>
      <div id="search-btn" class="fas fa-search"></div>
      <div id="user-btn" class="fas fa-user"></div>
      <div id="toggle-btn" class="fas fa-sun"></div>
   </div>

   <div class="profile">
      <img src="/public/img/pic-1-removebg-preview.png" alt="Perfil" />
      <h3><?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?></h3>
      <span><?= htmlspecialchars($_SESSION['papel'] ?? '') ?></span>
      <a href="profile.php" class="btn">ver perfil</a>
      <div class="flex-btn">
         <a href="login.php" class="option-btn">login</a>
         <a href="register.php" class="option-btn">register</a>
      </div>
      <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
   </div>
</section>
</header>

<div class="side-bar">
   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>
   <div class="profile">
         <img src="/public/img/pic-1-removebg-preview.png" alt="Perfil" />
         <h3><?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?></h3>
         <span><?= htmlspecialchars($_SESSION['papel'] ?? '') ?></span>
         <a href="profile.php" class="btn">ver perfil</a>
         <h3>por favor faça login ou registre-se</h3>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
   </div>
   <nav class="navbar">
      <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="aula_cadastro.php"><i class="fa-solid fa-bars-staggered"></i><span>Cadastro de Aula</span></a>
      <a href="register_student.php"><i class="fas fa-graduation-cap"></i><span>Cadastro de Aluno</span></a>
      <a href="register.php"><i class="fas fa-comment"></i><span>Cadastro de Funcionário</span></a>
      <a href="update_content.php"><i class="fas fa-comment"></i><span>Cadastro de Materiais</span></a>
      <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
   </nav>
</div>

<section class="form-container">
   <?php if (isset($_SESSION['mensagem'])): ?>
      <div class="message form">
         <span><?= htmlspecialchars($_SESSION['mensagem']) ?></span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      <?php unset($_SESSION['mensagem']); // Clear the message after displaying ?>
   <?php endif; ?>

   <form class="register" action="../controller/ProfessorController.php?acao=cadastrarOuAtualizar" method="post">
      <div class="flex">
         <div class="col">
            <p>Nome completo do professor: <span>*</span></p>
            <input type="text" id="nome_professor" name="nome" placeholder="Digite o nome completo" maxlength="100" required class="box" value="<?= htmlspecialchars($nome) ?>" />

            <p>CPF do professor: <span>*</span></p>
            <input type="text" id="cpf_professor" name="cpf" placeholder="Digite o CPF" maxlength="14" required class="box" value="<?= htmlspecialchars($cpf) ?>" onblur="buscarProfessorPorCpf(this.value)" />

            <p>Data de nascimento: <span>*</span></p>
            <input type="date" id="data_nascimento_professor" name="data_nascimento" required class="box" value="<?= htmlspecialchars($data_nascimento) ?>" />

            <p>Especialidade do professor: <span>*</span></p>
            <select name="especialidade" id="especialidade_professor" class="box" required>
               <option value="" disabled <?= empty($especialidade) ? 'selected' : '' ?>>-- selecione uma especialidade</option>
               <option value="Inglês" <?= ($especialidade === 'Inglês') ? 'selected' : '' ?>>Inglês</option>
               <option value="Espanhol" <?= ($especialidade === 'Espanhol') ? 'selected' : '' ?>>Espanhol</option>
               <option value="Francês" <?= ($especialidade === 'Francês') ? 'selected' : '' ?>>Francês</option>
               <option value="Libras" <?= ($especialidade === 'Libras') ? 'selected' : '' ?>>Libras</option>
               <option value="Alemão" <?= ($especialidade === 'Alemão') ? 'selected' : '' ?>>Alemão</option>
               <option value="Tailandês" <?= ($especialidade === 'Tailandês') ? 'selected' : '' ?>>Tailandês</option>
            </select>
         </div>
         <div class="col">
            <p>Senha do professor: <span>*</span></p>
            <input type="password" id="senha_professor" name="senha" placeholder="Digite a senha" maxlength="20" class="box" autocomplete="new-password" />

            <p>Confirme a senha: <span>*</span></p>
            <input type="password" id="confirmar_senha_professor" name="confirmar_senha" placeholder="Confirme a senha" maxlength="20" class="box" autocomplete="new-password" />

            <p>Cargo: <span>*</span></p>
            <input type="text" name="cargo" value="professor" readonly class="box" />

            <p>E-mail do professor: <span>*</span></p>
            <input type="email" id="email_professor" name="email" placeholder="Digite o e-mail" maxlength="100" required class="box" value="<?= htmlspecialchars($email) ?>" />

            <p>Telefone do professor: <span>*</span></p>
            <input type="tel" id="telefone_professor" name="telefone" placeholder="Digite o telefone" required class="box" value="<?= htmlspecialchars($telefone) ?>" />
         </div>
      </div>

      <input type="submit" value="Salvar/Atualizar Professor" class="btn" />
   </form>
</section>

<script src="/public/js/admin_script.js"></script>

<script>
   // Function to format CPF (optional, for better UX)
   document.getElementById('cpf_professor').addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, ''); // Remove all non-digits
      if (value.length > 9) {
         value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
      } else if (value.length > 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{3})/, '$1.$2.$3');
      } else if (value.length > 3) {
            value = value.replace(/(\d{3})(\d{3})/, '$1.$2');
      }
      e.target.value = value;
   });

   // Function to search for professor via AJAX
   function buscarProfessorPorCpf(cpf) {
      // Only trigger search if CPF has the expected length after formatting
      if (cpf.length === 14 || cpf.replace(/\D/g, '').length === 11) {
         // Remove non-numeric characters before sending to backend
         const cpfLimpo = cpf.replace(/\D/g, '');

         fetch('../controller/ProfessorController.php?acao=buscarProfessor&cpf=' + cpfLimpo)
            .then(response => response.json())
            .then(data => {
               if (data.success && data.professor) {
                  // Professor found, populate the form fields
                  document.getElementById('nome_professor').value = data.professor.nome;
                  document.getElementById('data_nascimento_professor').value = data.professor.data_nascimento;
                  document.getElementById('especialidade_professor').value = data.professor.especialidade;
                  document.getElementById('email_professor').value = data.professor.email;
                  document.getElementById('telefone_professor').value = data.professor.telefone;
                  // Clear password fields for security
                  document.getElementById('senha_professor').value = '';
                  document.getElementById('confirmar_senha_professor').value = '';
                  alert('Professor encontrado! Preencha a senha novamente (se desejar alterar) e outros dados para atualizar.');
               } else {
                  // Professor not found, clear relevant fields (except CPF) and notify
                  document.getElementById('nome_professor').value = '';
                  document.getElementById('data_nascimento_professor').value = '';
                  document.getElementById('especialidade_professor').value = ''; // or set a default value
                  document.getElementById('email_professor').value = '';
                  document.getElementById('telefone_professor').value = '';
                  document.getElementById('senha_professor').value = '';
                  document.getElementById('confirmar_senha_professor').value = '';
                  alert('Professor não encontrado. Você pode cadastrar um novo.');
               }
            })
            .catch(error => {
               console.error('Erro ao buscar professor:', error);
               alert('Erro ao buscar professor. Tente novamente.');
            });
      }
   }

   // Dark mode toggle logic
   let darkMode = localStorage.getItem('dark-mode');
   let body = document.body;

   const enableDarkMode = () => {
      body.classList.add('dark');
      localStorage.setItem('dark-mode', 'enabled');
   };

   const disableDarkMode = () => {
      body.classList.remove('dark');
      localStorage.setItem('dark-mode', 'disabled');
   };

   if (darkMode === 'enabled') {
      enableDarkMode();
   } else {
      disableDarkMode();
   }
</script>

</body>
</html>