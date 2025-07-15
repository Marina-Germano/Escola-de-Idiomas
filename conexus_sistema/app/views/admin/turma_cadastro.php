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
<!-- <body style="padding-left: 0;"> -->
<body>
<?php include '../components/admin_header.php'; ?>

<section class="form-container">
<form class="register" action="" method="post" enctype="multipart/form-data">
  <div class="flex">
    <div class="col">

      <p>Idioma <span>*</span></p>
      <select name="ididioma" class="box" required>
          <option value="" disabled selected>-- selecione o idioma --</option>
          <option value="1">Inglês</option>
          <option value="2">Espanhol</option>
          <option value="3">Francês</option>
          <option value="4">Libras</option>
          <option value="5">Alemão</option>
          <option value="6">Tailandês</option>
      </select>

      <p>Nível da Turma <span>*</span></p>
      <select name="idnivel" class="box" required>
          <option value="" disabled selected>-- selecione o nível --</option>
          <option value="1">Básico</option>
          <option value="2">Intermediário</option>
          <option value="3">Avançado</option>
      </select>

      <p>Funcionário Responsável <span>*</span></p>
        <select name="idfuncionario" class="box" required>
            <option value="" disabled selected>-- selecione o funcionário --</option>
            <?php
            $sql = "
                SELECT f.idfuncionario, u.nome 
                FROM funcionario f
                INNER JOIN usuario u ON f.idusuario = u.idusuario
                ORDER BY u.nome ASC
            ";
            
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['idfuncionario'] . '">' . htmlspecialchars($row['nome']) . '</option>';
                }
            } else {
                echo '<option disabled>Nenhum funcionário encontrado</option>';
            }
            ?>
        </select>

      <p>Nome da Turma (Descrição) <span>*</span></p>
      <input type="text" name="descricao" placeholder="Digite o nome da turma" maxlength="100" required class="box">

      <p>Dias da Semana <span>*</span></p>
      <div class="box">
        <label><input type="checkbox" name="dias_semana[]" value="Segunda"> Seg</label>
        <label><input type="checkbox" name="dias_semana[]" value="Terça"> Ter</label>
        <label><input type="checkbox" name="dias_semana[]" value="Quarta"> Qua</label>
        <label><input type="checkbox" name="dias_semana[]" value="Quinta"> Qui</label>
        <label><input type="checkbox" name="dias_semana[]" value="Sexta"> Sex</label>
        <label><input type="checkbox" name="dias_semana[]" value="Sábado"> Sáb</label>
      </div>

    </div>

    <div class="col">

      <p>Hora de Início da Turma <span>*</span></p>
      <input type="time" name="hora_inicio" required class="box">

      <p>Capacidade Máxima <span>*</span></p>
      <input type="number" name="capacidade_maxima" min="1" required class="box" placeholder="Ex: 20">

      <p>Sala da Turma <span>*</span></p>
      <input type="text" name="sala" maxlength="100" required class="box" placeholder="Ex: Sala 5, Bloco B">

      <p>Tipo de Recorrência <span>*</span></p>
      <select name="tipo_recorrencia" class="box" required>
          <option value="" disabled selected>-- selecione --</option>
          <option value="diaria">Diária</option>
          <option value="semanal">Semanal</option>
          <option value="mensal">Mensal</option>
      </select>

      <p>Imagem da Turma (opcional)</p>
      <input type="file" name="imagem" accept="image/*" class="box">

    </div>
  </div>
  <input type="submit" name="submit" value="Cadastrar Turma" class="btn">
</form>
</section>

<script src="../../../public/js/admin_script.js"></script>

</body>
</html>
