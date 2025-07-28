<?php
require_once(__DIR__ . '/../../config/conexao.php');
$conn = Conexao::conectar();
?>


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

<?php
require_once(__DIR__ . '/../../models/turma.php');
require_once(__DIR__ . '/../../models/idioma.php');
require_once(__DIR__ . '/../../models/nivel.php');


$conn = Conexao::conectar();
$turmaModel = new Turma();

$idiomaModel = new Idioma();
$idiomas = $idiomaModel->listarTodos();

$nivelModel = new Nivel();
$niveis = $nivelModel->listarTodos();

$stmt = $conn->prepare("SELECT tipo_recorrencia FROM turma");
$stmt->execute();
$tiposRecorrencia = $stmt->fetchAll(PDO::FETCH_ASSOC);

$modoEdicao = false;
$item = [];

if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
    $modoEdicao = true;
    $item = $turmaModel->listarId($_GET['id']);
}

?>


<section class="form-container">
  <!-- <form class="register" action="/conexus_sistema/app/controllers/TurmaController.php?acao=cadastrar" method="post" enctype="multipart/form-data"> -->

<form class="register" action="../../controllers/turmaController.php?acao=<?= $modoEdicao ? 'alterar' : 'cadastrar' ?>"
method="post" enctype="multipart/form-data">
  <div class="flex">
    <div class="col">

      <p>Idioma <span>*</span></p>
      <select name="ididioma" class="box" required>
          <option value="" disabled selected>-- selecione o idioma --</option>
          <?php foreach ($idiomas as $idioma): ?>
              <option value="<?= $idioma['ididioma'] ?>" <?= isset($item['ididioma']) && $item['ididioma'] == $idioma['ididioma'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($idioma['descricao']) ?>
              </option>
          <?php endforeach; ?></select>

      <p>Nível da Turma <span>*</span></p>
      <select name="idnivel" class="box" required>
          <option value="" disabled selected>-- selecione o nível --</option>
          <?php foreach ($niveis as $nivel): ?>
              <option value="<?= $nivel['idnivel'] ?>" <?= isset($item['idnivel']) && $item['idnivel'] == $nivel['idnivel'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($nivel['descricao']) ?>
              </option>
          <?php endforeach; ?></select>

      <p>Professor Responsável <span>*</span></p>
        <select name="idfuncionario" class="box" required>
            <option value="" disabled selected>-- selecione o funcionário --</option>
            <?php
            $stmt = $conn->prepare("
                SELECT f.idfuncionario, u.nome
                FROM funcionario f
                INNER JOIN usuario u ON f.idusuario = u.idusuario where f.cargo = 'professor'
                ORDER BY u.nome ASC");
            //quero que seja apenas funcionarios com papel de professor, como eu faço?
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result && count($result) > 0) {
                foreach ($result as $row) {
                  if ($row['idfuncionario'] == $item['idfuncionario']) {
                      echo '<option value="' . $row['idfuncionario'] . '" selected>' . htmlspecialchars($row['nome']) . '</option>';
                  } else {
                    echo '<option value="' . $row['idfuncionario'] . '">' . htmlspecialchars($row['nome']) . '</option>';
                  }
                }
            }
            else {
                echo '<option disabled>Nenhum funcionário encontrado</option>';}
            ?>
        </select>

      <p>Nome da Turma (Descrição) <span>*</span></p>
      <input type="text" name="descricao" placeholder="Digite o nome da turma"  value="<?= $modoEdicao && isset($item['descricao']) ? htmlspecialchars($item['descricao']) : '' ?>" maxlength="100" required class="box">

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

      <p>Hora de Início da Aula<span>*</span></p>
      <input type="time" name="hora_inicio"  value="<?= $modoEdicao && isset($item['hora_inicio']) ? htmlspecialchars($item['hora_inicio']) : '' ?>" required class="box">

      <p>Capacidade Máxima <span>*</span></p>
      <input type="number" name="capacidade_maxima" min="1" required class="box"  value="<?= $modoEdicao && isset($item['capacidade_maxima']) ? htmlspecialchars($item['capacidade_maxima']) : '' ?>" placeholder="Ex: 20">

      <p>Sala da Turma <span>*</span></p>
      <input type="text" name="sala" maxlength="100" required class="box"  value="<?= $modoEdicao && isset($item['sala']) ? htmlspecialchars($item['sala']) : '' ?>" placeholder="Ex: Sala 5, Bloco B">

      <p>Tipo de Recorrência <span>*</span></p>
      <select name="tipo_recorrencia" class="box" required>
      <option value="" disabled <?= empty($item['tipo_recorrencia']) ? 'selected' : '' ?>>-- selecione --</option>
        <?php foreach ($tiposRecorrencia as $rec): ?>
          <option value="<?= $rec['tipo_recorrencia'] ?>" <?= isset($item['tipo_recorrencia']) && $item['tipo_recorrencia'] === $rec['tipo_recorrencia'] ? 'selected' : '' ?>>
            <?= ucfirst($rec['tipo_recorrencia']) ?>
          </option>
        <?php endforeach; ?>
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
