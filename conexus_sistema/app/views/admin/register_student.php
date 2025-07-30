<?php
require_once(__DIR__ . '/../../config/conexao.php');
require_once(__DIR__ . '/../../models/aluno.php');
require_once(__DIR__ . '/../../models/usuario.php');

$conn = Conexao::conectar();
$alunoModel = new Aluno();

$modoEdicao = false;
$item = [];

if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
    $modoEdicao = true;
    $item = $alunoModel->listarId($_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conexus - <?= $modoEdicao ? 'Editar Aluno' : 'Registro Aluno' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../../public/css/admin_style.css" />
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">
    <form
        class="register"
        action="../../controllers/alunoController.php?acao=<?= $modoEdicao ? 'alterar' : 'cadastrarCompleto' ?>"
        method="post"
        enctype="multipart/form-data"
    >
        <input type="hidden" name="papel" value="aluno" />
        <?php if ($modoEdicao): ?>
            <input type="hidden" name="idaluno" value="<?= $item['idaluno'] ?>">
        <?php endif; ?>

        <div class="flex">
            <div class="col">
                <p>Nome do Aluno: <span>*</span></p>
                <input type="text" name="nome" required class="box"
                    value="<?= $modoEdicao && isset($item['nome']) ? htmlspecialchars($item['nome']) : '' ?>" placeholder="Nome Completo">

                <p>CPF do Aluno: <span>*</span></p>
                <input type="text" name="cpf" maxlength="11" required class="box"
                    value="<?= $modoEdicao && isset($item['cpf']) ? htmlspecialchars($item['cpf']) : '' ?>">

                <p>Data de Nascimento: <span>*</span></p>
                <input type="date" name="data_nascimento" required class="box"
                    value="<?= $modoEdicao && isset($item['data_nascimento']) ? ($item['data_nascimento']) : '' ?>">

                <p>Email do Aluno: <span>*</span></p>
                <input type="email" name="email" required class="box"
                    value="<?= $modoEdicao && isset($item['email']) ? htmlspecialchars($item['email']) : '' ?>">

                <p>Telefone do Aluno: <span>*</span></p>
                <input type="tel" name="telefone" maxlength="15" required class="box"
                    value="<?= $modoEdicao && isset($item['telefone']) ? htmlspecialchars($item['telefone']) : '' ?>">

                <p>CEP: <span>*</span></p>
                <input type="text" name="cep" maxlength="9" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['cep']) : '' ?>">

                <p>Rua: <span>*</span></p>
                <input type="text" name="rua" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['rua']) : '' ?>">
            </div>

            <div class="col">

                <p>Número: <span>*</span></p>
                <input type="text" name="numero" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['numero']) : '' ?>">

                <p>Bairro: <span>*</span></p>
                <input type="text" name="bairro" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['bairro']) : '' ?>">

                <p>Complemento:</p>
                <input type="text" name="complemento" class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['complemento']) : '' ?>">

                <p>Responsável pelo Aluno: <span>*</span></p>
                <input type="text" name="responsavel" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['responsavel']) : '' ?>">

                <p>Telefone do Responsável: <span>*</span></p>
                <input type="tel" name="tel_responsavel" required class="box"
                    value="<?= $modoEdicao ? htmlspecialchars($item['tel_responsavel']) : '' ?>">

                <?php if (!$modoEdicao): ?>
                    <p>Senha: <span>*</span></p>
                    <input type="password" name="senha" maxlength="20" required class="box">
                <?php endif; ?>

                <p>Situação do Aluno:</p>
                <select name="situacao" class="box">
                    <option value="ativo" <?= $modoEdicao && $item['situacao'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="inativo" <?= $modoEdicao && $item['situacao'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                </select>

                <br /><br />
                <p style="font-size: 14px;">
                    <?= $modoEdicao ? 'Edite os item do aluno conforme necessário.' : 'Ao clicar em "Registrar", o aluno será vinculado ao CPF informado e seu cadastro completo será salvo.' ?>
                </p>
            </div>
        </div>

        <input type="submit" name="submit" value="<?= $modoEdicao ? 'Salvar Alterações' : 'Registrar' ?>" class="btn"/>
    </form>
</section>

<script src="../../../public/js/admin_script.js"></script>

</body>
</html>
