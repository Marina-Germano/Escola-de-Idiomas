<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Entre em Contato - Conexus</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
/>

<link rel="stylesheet" href="../../../public/css/style.css">

</head>
<body>
<?php 
include __DIR__ . '/../components/student_header.php';
?>

    <section class="contact">

    <div class="row">

        <div class="image">
            <img src="../public/img/contact_img.png" alt="Imagem de Contato">
        </div>

    <form action="../controllers/contatoController.php" method="post" enctype="multipart/form-data">
            <h3>Entre em Contato:</h3>
            <?php 
            if (!empty($mensagemFeedback)): 
            ?>
                <div class="message form">
                    <span><?= htmlspecialchars($mensagemFeedback) ?></span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>
            <?php endif; ?>

            <input type="text" placeholder=" digite seu nome: " name="name" required maxlength="50" class="box" value="<?= $nomeCampo ?? '' ?>">
            <input type="email" placeholder=" digite seu e-mail: " name="email" required maxlength="50" class="box">
            <input type="number" placeholder=" digite seu telefone: " name="number" required maxlength="50" class="box">
            <input type="number" placeholder=" digite seu numero de matricula" name="matricula" required maxlength="50" class="box" value="<?= $matriculaCampo ?? '' ?>">
            <select id="reason" name="razao" class="box" required>
                <option value="">escolha a razão de contato:</option>
                <option value="Mudar informações cadastro">Mudar informações cadastro</option>
                <option value="Atualizar opções de pagamento">Atualizar opções de pagamento</option>
                <option value="Agendamento de aula/prova substitutiva">Agendamento de aula/prova substitutiva</option>
                <option value="Outro">Outro</option>
            </select>
            <input type="file" name="anexo" class="box">
            <textarea name="msg" class="box" placeholder=" digite a mensagem (Opcional):" maxlength="1000" cols="30" rows="10"></textarea>
            <input type="submit" value="enviar" class="inline-btn" name="submit_contato"> </form>

    </div>

    <div class="box-container">

        <div class="box">
            <i class="fas fa-phone"></i>
            <h3>telefone de contato</h3>
            <a href="tel:1234567890">123-456-7890</a>
            <a href="tel:1112223333">111-222-3333</a>
        </div>
        
        <div class="box">
            <i class="fas fa-envelope"></i>
            <h3>email de contato</h3>
            <a href="mailto:aleequintogti@gmail.com">aleequintogti@gmail.com</a>
            <a href="mailto:anasbhai@gmail.com">anasbhai@gmail.com</a>
        </div>

        <div class="box">
            <i class="fas fa-map-marker-alt"></i>
            <h3>endereço</h3>
            <a href="#"> Rua das Flores, 123 - Bairro Primavera, Cidade Sol Nascente, SP - CEP 12345-678 </a>
        </div>

    </div>

</section>
<script src="../../../public/js/script.js"></script>
</body>
</html>