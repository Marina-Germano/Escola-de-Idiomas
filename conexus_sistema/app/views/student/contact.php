<?php
// views/student/contact.php

// Fofoca: Inicia a sessão APENAS se ainda não estiver iniciada.
if (session_status() == PHP_SESSION_NONE){
    session_start();
}

// Fofoca: Puxando as informações do usuário da sessão para deixar o cabeçalho e sidebar dinâmicos!
// Garante que as variáveis existam, mesmo que a sessão não tenha tudo setado.
$idUsuario = $_SESSION['idusuario'] ?? null;
$nomeUsuario = $_SESSION['nome_usuario'] ?? 'Usuário Desconhecido';
$matriculaUsuario = $_SESSION['matricula'] ?? 'N/A';
$fotoPerfil = $_SESSION['foto_perfil'] ?? '/conexus_sistema/public/img/default-profile.png';

// Fofoca: Pré-popula os campos do formulário se o usuário estiver logado
$nomeCampo = ($nomeUsuario !== 'Usuário Desconhecido') ? htmlspecialchars($nomeUsuario) : '';
$matriculaCampo = ($matriculaUsuario !== 'N/A') ? htmlspecialchars($matriculaUsuario) : '';

// Fofoca: Mensagens de feedback da sessão
$mensagemFeedback = '';
if (isset($_SESSION['mensagem_contato'])) {
    $mensagemFeedback = $_SESSION['mensagem_contato'];
    unset($_SESSION['mensagem_contato']); // Limpa a mensagem depois de usar
}
?>

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
<header class="header">
<section class="flex">
<a href="/conexus_sistema/app/views/student/home.php" class="logo"> <img src="/conexus_sistema/public/img/conexus_sem_fundo.png" alt="Logo Conexus"> Conexus </a>
<form action="" method="post" class="search-form">
    <input type="text" name="search_box" placeholder="Pesquisar..."  required maxlength="100" id="search_box">
    <button type="submit" class="bi bi-search" name="search_box"></button>
</form>
<div class="icons">
    <div id="menu-btn" class="bi bi-list"></div>
    <div id="search-btn" class="bi bi-search"></div>
    <div id="user-btn" class="bi bi-person"></div>
    <div id="toggle-btn" class="bi bi-brightness-high"></div>
</div>

<div class="profile">
    <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil">
    <h3><?= htmlspecialchars($nomeUsuario) ?></h3>
    <span><?= htmlspecialchars($matriculaUsuario) ?></span>
    <a href="profile.html" class="btn">acessar perfil</a>
    <div class="flex-btn">
        <?php if (!isset($_SESSION['idusuario'])): ?>
            <a href="/conexus_sistema/app/views/login.php" class="option-btn">login</a>
        <?php endif; ?>
        <a href="/conexus_sistema/app/controllers/userController.php?acao=logout" class="option-btn">sair</a>
    </div>
</div>
</section>
</header>
<div class="side-bar">

    <div id="close-btn">
        <i class="fas fa-times"></i>
    </div>

    <div class="profile">
        <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil">
        <h3><?= htmlspecialchars($nomeUsuario) ?></h3>
        <span><?= htmlspecialchars($matriculaUsuario) ?></span>
        <a href="profile.html" class="btn">acessar perfil</a>
    </div>

    <nav class="navbar">
        <a href="home.php"><i class="fas fa-home"></i><span>home</span></a>
        <a href="aulas.php"><i class="bi bi-calendar-week"></i><span>aulas</span></a>
        <a href="materiais.php"><i class="bi bi-folder-check"></i><span>materiais</span></a>
        <a href="boletim.php"><i class="bi bi-reception-4"></i><span>boletim</span></a>
        <a href="financeiro.php"><i class="bi bi-piggy-bank-fill"></i><span>financeiro</span></a>
        <a href="contact.php"><i class="fas fa-headset"></i><span>nos contate</span></a>
    </nav>
    </div>
    <section class="contact">

    <div class="row">

        <div class="image">
            <img src="/conexus_sistema/public/img/contact_img.png" alt="Imagem de Contato">
        </div>

        <form action="/conexus_sistema/app/controllers/contatoController.php" method="post" enctype="multipart/form-data">
            <h3>Entre em Contato:</h3>
            <?php if (!empty($mensagemFeedback)): ?>
                <div class="message form">
                    <span><?= htmlspecialchars($mensagemFeedback) ?></span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>
            <?php endif; ?>

            <input type="text" placeholder=" digite seu nome: " name="name" required maxlength="50" class="box" value="<?= $nomeCampo ?>">
            <input type="email" placeholder=" digite seu e-mail: " name="email" required maxlength="50" class="box">
            <input type="number" placeholder=" digite seu telefone: " name="number" required maxlength="50" class="box">
            <input type="number" placeholder=" digite seu numero de matricula" name="matricula" required maxlength="50" class="box" value="<?= $matriculaCampo ?>">
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