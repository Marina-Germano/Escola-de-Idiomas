<?php
require_once(__DIR__ . '/../../config/conexao.php'); // ajuste o caminho conforme seu projeto
$conn = Conexao::conectar(); // cria a conexão para ser usada no header
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conexus - Registro Aluno</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../../../public/css/admin_style.css" />
</head>
<body style="padding-left: 0;">

<!-- Cabeçalho -->
<?php include '../components/admin_header.php'; ?>

<!-- Formulário -->
<section class="form-container">
    <form
        class="register"
        action="/conexus_sistema/app/controllers/alunoController.php?acao=cadastrarCompleto"
        method="post"
        enctype="multipart/form-data"
    >
        <input type="hidden" name="papel" value="aluno" />

        <div class="flex">
            <div class="col">
                <p>Nome do Aluno: <span>*</span></p>
                <input type="text" name="nome" placeholder="Digite o nome do aluno" maxlength="100" required class="box" />

                <p>CPF do Aluno: <span>*</span></p>
                <input type="text" name="cpf" placeholder="Digite o CPF" maxlength="11" required class="box" />

                <p>Data de Nascimento: <span>*</span></p>
                <input type="date" name="data_nascimento" required class="box" />

                <p>Email do Aluno: <span>*</span></p>
                <input type="email" name="email" placeholder="Digite o email" maxlength="100" required class="box" />

                <p>Telefone do Aluno: <span>*</span></p>
                <input type="tel" name="telefone" placeholder="Digite o telefone" maxlength="15" required class="box" />

                <p>CEP: <span>*</span></p>
                <input type="text" name="cep" placeholder="Digite o CEP" maxlength="9" required class="box" />

                <p>Rua: <span>*</span></p>
                <input type="text" name="rua" placeholder="Digite a Rua" required class="box" />

                <p>Número: <span>*</span></p>
                <input type="text" name="numero" placeholder="Número da residência" required class="box" />

                <p>Bairro: <span>*</span></p>
                <input type="text" name="bairro" placeholder="Digite o Bairro" required class="box" />

                <p>Complemento:</p>
                <input type="text" name="complemento" placeholder="Complemento (opcional)" class="box" />
            </div>

            <div class="col">
                <p>Responsável pelo Aluno: <span>*</span></p>
                <input type="text" name="responsavel" placeholder="Nome do responsável" required class="box" />

                <p>Telefone do Responsável: <span>*</span></p>
                <input type="tel" name="tel_responsavel" placeholder="Telefone do responsável" required class="box" />

                <p>Senha: <span>*</span></p>
                <input type="password" name="senha" placeholder="Crie uma senha" maxlength="20" required class="box" />

                <p>Foto de Perfil: <span>*</span></p>
                <input type="file" name="foto" accept="image/*" required class="box" />

                <p>Situação do Aluno:</p>
                <select name="situacao" class="box">
                    <option value="ativo" selected>Ativo</option>
                    <option value="inativo">Inativo</option>
                </select>

                <br /><br />
                <p style="font-size: 14px;">
                    Ao clicar em "Registrar", o aluno será vinculado ao CPF informado e seu cadastro completo será salvo.
                </p>
            </div>
        </div>

        <input type="submit" name="submit" value="Registrar Aluno" class="btn" />
    </form>
</section>

<script src="../../../public/js/admin_script.js"></script>

</body>
</html>
