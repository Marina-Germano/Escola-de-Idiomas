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

    <form action="../../controllers/materialController.php?acao=cadastrar"
    method="POST" enctype="multipart/form-data">
        <!-- <h2 style="margin-bottom: 20px;">Cadastro Completo de Material</h2> -->

        <div class="flex">
            <div class="col">

                <p><strong>Título <span>*</span></strong></p>
                <input type="text" name="titulo" maxlength="255" required placeholder="Título do material" class="box">

                <p><strong>Tipo de Material</strong></p>
                <select name="idtipo_material" class="box">
                    <option value="" selected>Selecione existente (ou preencha abaixo)</option>
                    <option value="1">Livro</option>
                    <option value="2">PDF</option>
                    <option value="3">Vídeo</option>
                </select>
                <input type="text" name="descricao_tipo_material" class="box" placeholder="Cadastrar novo tipo de material">

                <p><strong>Idioma</strong></p>
                <select name="ididioma" class="box">
                    <option value="" selected>Selecione existente (ou preencha abaixo)</option>
                    <option value="1">Inglês</option>
                    <option value="2">Espanhol</option>
                    <option value="3">Francês</option>
                </select>
                <input type="text" name="descricao_idioma" class="box" placeholder="Cadastrar novo idioma">

                <p><strong>Nível</strong></p>
                <select name="idnivel" class="box">
                    <option value="" selected>Selecione existente (ou preencha abaixo)</option>
                    <option value="1">Básico</option>
                    <option value="2">Intermediário</option>
                    <option value="3">Avançado</option>
                </select>
                <input type="text" name="descricao_nivel" class="box" placeholder="Cadastrar novo nível">

                <p><strong>Arquivo <span>*</span></strong></p>
                <input type="file" name="arquivo" accept=".pdf,.doc,.docx,.mp4,.jpg,.png" required class="box">

                <p><strong>ID do Professor <span>*</span></strong></p>
                <input type="number" name="idprofessor" required placeholder="ID do professor responsável" class="box">

            </div>

            <div class="col">

                <p><strong>Turma</strong></p>
                <select name="idturma" class="box">
                    <option value="" selected>Selecione existente (ou preencha abaixo)</option>
                    <option value="1">Turma A</option>
                    <option value="2">Turma B</option>
                    <option value="3">Turma C</option>
                </select>

                <input type="text" name="descricao_turma" class="box" placeholder="Descrição da nova turma">
                <input type="text" name="dias_semana" class="box" placeholder="Dias da semana (ex: Seg, Qua)">
                <input type="time" name="hora_inicio" class="box" placeholder="Hora de início">
                <input type="number" name="capacidade_maxima" class="box" placeholder="Capacidade máxima">
                <input type="text" name="sala" class="box" placeholder="Sala da turma">
                <input type="text" name="tipo_recorrencia" class="box" placeholder="Tipo de recorrência (ex: semanal)">

                <p><strong>Descrição do Material</strong></p>
                <textarea name="descricao_material" class="box" rows="5" placeholder="Descrição do material"></textarea>

                <p><strong>Quantidade <span>*</span></strong></p>
                <input type="number" name="quantidade" min="1" required class="box">

            </div>
        </div>

        <input type="submit" value="Cadastrar Material Completo" class="btn">

    </form>
</section>

<script src="../../../public/js/admin_script.js"></script>

</body>
</html>
