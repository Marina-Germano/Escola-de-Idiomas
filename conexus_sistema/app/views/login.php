<?php
ob_start(); //adionado para testes
require_once(__DIR__ . '/../config/conexao.php');

if (isset($_POST['submit'])) {
   $cpf = $_POST['cpf'] ?? '';
   $cpf = filter_var($cpf, FILTER_SANITIZE_STRING);

   $pass = $_POST['pass'] ?? '';
   //$pass = hash('sha256', $pass); //- comentada temporariamente para testes
// Depois (sem hash - compara com o texto puro do banco)
   $pass = $_POST['pass'];
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $conn = Conexao::conectar();

   $stmt = $conn->prepare("SELECT * FROM usuario WHERE cpf = ? AND senha = ?");
   $stmt->execute([$cpf, $pass]);
   $user = $stmt->fetch(PDO::FETCH_ASSOC);

   if ($user) {
      session_start();
      $_SESSION['idusuario'] = $user['idusuario'];
      $_SESSION['papel'] = $user['papel'];

      
        // Redirecionamento baseado no papel
      if ($user['papel'] === 'admin') {
         header('location:admin/dashboard.php');
      exit;
      }
      
      elseif ($user['papel'] === 'aluno') {
         header('Location: student/home.php');
         exit;
      }
      elseif ($user['papel'] === 'funcionario') {
         // Consulta o cargo na tabela funcionario
         $stmtCargo = $conn->prepare("SELECT cargo FROM funcionario WHERE idusuario = ?");
         $stmtCargo->execute([$user['idusuario']]);
         $func = $stmtCargo->fetch(PDO::FETCH_ASSOC);

         echo '<pre>';
var_dump($func);
echo '</pre>';
//exit;

         if ($func!=false && strtolower(trim($func['cargo'])) === 'professor') {
            $_SESSION['papel'] = "professor";
            header('location:teacher/home.php');
            exit;
         }
         else {
            echo "Acesso negado: cargo de funcionário não autorizado.";
            exit;
         }
      }

      else {
         echo "Papel de usuário inválido.";
      }

      exit;
   } else {
      $mensagem = "CPF ou senha incorretos!";
   }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - Conexus</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../../public/css/admin_style.css">
   <link rel="stylesheet" href="../../public/css/login_style.css">
</head>
<body>

<section class="form-container">
   <?php if (isset($mensagem)) : ?>
      <div class="message form">
            <span><?= $mensagem ?></span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
   <?php endif; ?>

   <form action="" method="post" class="login">
      <h3>Bem-vindo de volta!</h3>

      <p>Seu CPF <span>*</span></p>
      <input type="text" name="cpf" placeholder="Digite seu CPF" maxlength="11" required class="box">

      <p>Sua senha <span>*</span></p>
      <input type="password" name="pass" placeholder="Digite sua senha" maxlength="20" required class="box">

      <p class="link">Não tem uma conta? <a href="register.php">Registrar</a></p>
      <input type="submit" name="submit" value="Entrar" class="btn">
   </form>
</section>

<script>
   let darkMode = localStorage.getItem('dark-mode');
   let body = document.body;

   const enableDarkMode = () => {
      body.classList.add('dark');
      localStorage.setItem('dark-mode', 'enabled');
   }

   const disableDarkMode = () => {
      body.classList.remove('dark');
      localStorage.setItem('dark-mode', 'disabled');
   }

   if (darkMode === 'enabled') {
      enableDarkMode();
   } else {
      disableDarkMode();
   }
</script>

</body>
</html>

