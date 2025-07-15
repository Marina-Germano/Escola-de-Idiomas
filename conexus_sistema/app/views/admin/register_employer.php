<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Conexus - Registro Funcionário</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="../../../public/css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">
  <form class="register" 
  action="../../controllers/funcionarioController.php?acao=cadastrar"
  method="post" enctype="multipart/form-data">
    <div class="flex">
      <div class="col">
        <p>Nome do Funcionário: <span>*</span></p>
        <input type="text" name="name" placeholder="Digite o nome do funcionário" maxlength="50" required class="box" value="Nome">

        <p>CPF do Funcionário:</p>
        <input type="text" name="cpf" placeholder="Digite o cpf do funcionário" maxlength="11" required class="box" value="CPF">

        <p>Data de Nascimento:</p>
        <input type="date" name="data-nasc" required class="box" value="VALOR_NASC">

        <p>Cargo:</p>
        <input type="text" name="cargo" placeholder="Digite o cargo do funcionário" maxlength="50" required class="box" value="Cargo">

        <p>Especialidade do Professor:</p>
        <select name="profession" class="box">
          <option value="" disabled selected>-- selecione uma especialidade</option>
          <option value="administracao">Administração</option>
          <option value="p-ingles">Inglês</option>
          <option value="p-espanhol">Espanhol</option>
          <option value="p-frances">Francês</option>
          <option value="p-libras">Libras</option>
          <option value="p-alemao">Alemão</option>
          <option value="p-tailandes">Tailandês</option>
        </select>
      </div>
      <div class="col">
        <p>Senha do funcionário: <span>*</span></p>
        <input type="password" name="pass" placeholder="Digite a senha do funcionário" maxlength="20" required class="box">

        <p>Confirme a senha: <span>*</span></p>
        <input type="password" name="cpass" placeholder="Confirme a senha do funcionário" maxlength="20" required class="box">

        <p>Email do Funcionário: <span>*</span></p>
        <input type="email" name="email" placeholder="Digite o email do funcionário" maxlength="50" required class="box" value="Email">

        <p>Telefone do Funcionário:</p>
        <input type="tel" name="tel" placeholder="Digite o telefone do funcionário" required class="box" value="Telefone">

        <p>Selecione uma Foto de Perfil: <span>*</span></p>
        <input type="file" name="image" accept="image/*" required class="box">
      </div>
    </div>
    <input type="submit" name="submit" value="Registrar" class="btn">
  </form>
</section>

<script src="../../../public/js/admin_script.js"></script>

</body>
</html>
