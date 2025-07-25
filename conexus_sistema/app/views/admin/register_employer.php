<?php
require_once(__DIR__ . '/../../config/conexao.php');
require_once(__DIR__ . '/../../models/funcionario.php');
require_once(__DIR__ . '/../../models/usuario.php');

$conn = Conexao::conectar();
$funcionarioModel = new Funcionario();

$modoEdicao = false;
$item = [];

if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
    $modoEdicao = true;
    $item = $funcionarioModel->listarId($_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conexus - <?= $modoEdicao ? 'Editar funcionario' : 'Registro funcionario' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../../public/css/admin_style.css" />
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">
    <form
        class="register"
        action="../../controllers/funcionarioController.php?acao=<?= $modoEdicao ? 'alterar' : 'cadastrarCompleto' ?>"
        method="post"
        enctype="multipart/form-data">
        <input type="hidden" name="papel" value="funcionario" />
        
        <?php if ($modoEdicao): ?>
            <input type="hidden" name="idfuncionario" value="<?= $item['idfuncionario'] ?>">
        <?php endif; ?>

        <div class="flex">
            <div class="col">
                <p>Nome: <span>*</span></p>
                <input type="text" name="nome" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['nome']) : '' ?>">

                <p>CPF: <span>*</span></p>
                <input type="text" name="cpf" maxlength="11" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['cpf']) : '' ?>">

                <p>Data de Nascimento: <span>*</span></p>
                <input type="date" name="data_nascimento" required class="box"
                    value="<?= $modoEdicao && isset($item['data_nascimento']) ? htmlspecialchars($item['data_nascimento']) : '' ?>">

                <p>Cargo: <span>*</span></p>
                <input type="text" name="cargo" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['cargo']) : '' ?>">

                <p>Especialidade do Professor:</p>
                <input type="text" name="especialidade" class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['especialidade']) : '' ?>">
            </div>

            <div class="col">
                
                <?php if (!$modoEdicao): ?>
                    <p>Senha: <span>*</span></p>
                    <input type="password" name="senha" maxlength="20" required class="box">

                    <p>Confirme sua Senha: <span>*</span></p>
                    <input type="password" name="confirma_senha" maxlength="20" required class="box">
                <?php endif; ?>

                <p>Email: <span>*</span></p>
                <input type="email" name="email" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['email']) : '' ?>">

                <p>Telefone: <span>*</span></p>
                <input type="tel" name="telefone" maxlength="15" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['telefone']) : '' ?>">

                <br /><br />
                <p style="font-size: 14px;">
                    <?= $modoEdicao
                        ? 'Edite os dados do funcionário conforme necessário.'
                        : 'Ao clicar em "Registrar", o funcionário será vinculado ao CPF informado e seu cadastro será salvo.' ?>
                </p>
            </div>
        </div>

        <input type="submit" name="submit" value="Cadastrar" class="btn"/>
    </form>
</section>

<script src="../../../public/js/admin_script.js"></script>

</body>
</html>
