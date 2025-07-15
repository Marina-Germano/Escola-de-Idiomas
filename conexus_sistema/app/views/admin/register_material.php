<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conexus - Cadastrar Materiais</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

    <form action="/conexus_sistema/app/controllers/MaterialController.php?acao=cadastrar" method="POST" enctype="multipart/form-data">
        <div class="flex">
            <div class="col">

                <p>Título <span>*</span></p>
                <input type="text" name="titulo" maxlength="255" required placeholder="Título do material" class="box">

                <p>Tipo de Material <span>*</span></p>
                <select name="idtipo_material" required class="box">
                    <option value="" disabled selected>Selecione...</option>
                    <option value="1">Livro</option>
                    <option value="2">PDF</option>
                    <option value="3">Vídeo</option>
                    <!-- Adicione conforme o seu banco -->
                </select>
                <input type="text" name="novo_idioma" class="box" placeholder="Digite novo tipo de material">

                <p>Idioma <span>*</span></p>
                <select name="ididioma" required class="box">
                    <option value="" disabled selected>Selecione...</option>
                    <option value="1">Inglês</option>
                    <option value="2">Espanhol</option>
                    <option value="3">Francês</option>
                    <!-- Adicione conforme o seu banco -->
                </select>
                <input type="text" name="novo_idioma" class="box" placeholder="Digite novo idioma">

                <p>Nível <span>*</span></p>
                <select name="idnivel" required class="box">
                    <option value="" disabled selected>Selecione...</option>
                    <option value="1">Básico</option>
                    <option value="2">Intermediário</option>
                    <option value="3">Avançado</option>
                </select>
                <input type="text" name="novo_nivel" class="box" placeholder="Digite novo nível">

                <p>Arquivo <span>*</span></p>
                <input type="file" name="arquivo" accept=".pdf,.doc,.docx,.mp4,.jpg,.png" required class="box">
            </div>

            <div class="col">
            <p>Turma <span>*</span></p>
                <select name="idturma" required class="box">
                    <option value="" disabled selected>Selecione...</option>
                    <option value="1">Turma A</option>
                    <option value="2">Turma B</option>
                    <option value="3">Turma C</option>
                    <!-- Popule dinamicamente se necessário -->
                </select>
                <input type="text" name="nova_turma" class="box" placeholder="Digite nova turma">

            <p>Descrição</p>
            <textarea name="descricao" class="box" rows="5" placeholder="Descrição do material"></textarea>

            <p>Quantidade <span>*</span></p>
            <input type="number" name="quantidade" min="1" required class="box">

            <p>ID do Professor <span>*</span></p>
            <input type="number" name="idprofessor" required placeholder="ID do professor responsável" class="box">
            </div>
        </div>
        <input type="submit" value="Cadastrar Material" class="btn">
    </form>
</section>

<script src="../../../public/js/admin_script.js"></script>

</body>
</html>
