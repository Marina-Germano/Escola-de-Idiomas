 //OBS : add o metodo listarPagamentosPorAluno mo model 
 // atualizaer consoller pra roda o arquivo 


<?php

if (session_status() == PHP_SESSION_NONE){
    session_start();
}


require_once __DIR__ . '/../controllers/pagamentoController.php';


if (!function_exists('estaLogado')) {
    function estaLogado() {
        return isset($_SESSION['idusuario']);
    }
}

if (!estaLogado()) {
   header('Location: /conexus_sistema/app/views/login.php');
   exit;
}

//  Puxando as informações do aluno
$idAluno = $_SESSION['idusuario'] ?? null;
$nomeAluno = $_SESSION['nome_usuario'] ?? 'Aluno Desconhecido'; 
$matriculaAluno = $_SESSION['matricula'] ?? 'N/A'; 
$fotoPerfil = $_SESSION['foto_perfil'] ?? '/conexus_sistema/public/img/default-profile.png'; 


$historicoPagamentos = [];
$erroFinanceiro = null;

if (!$idAluno) {
    $erroFinanceiro = "Ops! Não conseguimos identificar seus dados. Por favor, contate o suporte técnico.";
} else {
    try {
        // Instancia o Controller de Pagamento.
        $pagamentoController = new pagamentoController();

        // Chama o método do Controller para buscar o histórico do aluno.
        $historicoPagamentos = $pagamentoController->getHistoricoFinanceiroAluno($idAluno);

        // Verifica se houve erro
        if (isset($historicoPagamentos['error'])) {
            $erroFinanceiro = $historicoPagamentos['error'];
            $historicoPagamentos = []; // Limpa o array para não tentar exibir dados errados.
        } elseif (empty($historicoPagamentos)) {
            $erroFinanceiro = "Nenhum histórico de pagamentos encontrado.";
        }

    } catch (Exception $e) {
        error_log("Erro geral ao carregar financeiro para o aluno " . $idAluno . ": " . $e->getMessage());
        $erroFinanceiro = "Não foi possível carregar seu histórico financeiro. Tente novamente mais tarde.";
    }
}

?>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Financeiro - Conexus</title>

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
<a href="/conexus_sistema/app/views/student/home.html" class="logo"> <img src="/conexus_sistema/public/img/conexus_sem_fundo.png" alt=""> Conexus </a>
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
    <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil"> <h3><?= htmlspecialchars($nomeAluno) ?></h3> <span><?= htmlspecialchars($matriculaAluno) ?></span> <a href="profile.html" class="btn">acessar perfil</a>
    <div class="flex-btn">
        <?php if (!estaLogado()): ?>
            <a href="/conexus_sistema/app/login.php" class="option-btn">login</a>
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
        <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil"> <h3><?= htmlspecialchars($nomeAluno) ?></h3> <span><?= htmlspecialchars($matriculaAluno) ?></span> <a href="profile.html" class="btn">acessar perfil</a>
    </div>

    <nav class="navbar">
        <a href="home.html"><i class="fas fa-home"></i><span>home</span></a>
        <a href="aulas.php"><i class="bi bi-calendar-week"></i><span>aulas</span></a>
        <a href="materiais.html"><i class="bi bi-folder-check"></i><span>materiais</span></a>
        <a href="boletim.php"><i class="bi bi-reception-4"></i><span>boletim</span></a>
        <a href="financeiro.php"><i class="bi bi-piggy-bank-fill"></i><span>financeiro</span></a>
        <a href="contact.html"><i class="fas fa-headset"></i><span>nos contate</span></a>
    </nav>
    </div>
    <section class="courses">
        <h1 class="heading">Histórico Financeiro</h1>

        <?php if ($erroFinanceiro): ?>
            <p style="text-align:center; color:red; font-size: 16px; margin-top: 2rem;"><?= $erroFinanceiro ?></p>
        <?php elseif (!empty($historicoPagamentos)): ?>
        <div class="box-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; margin-top: 2rem;">
            <?php foreach ($historicoPagamentos as $pagamento): ?>
            <div class="box" style="flex-basis: calc(33.333% - 2rem); box-shadow: 0 .5rem 1rem rgba(0,0,0,.1); border-radius: .5rem; padding: 1.5rem; text-align: center;">
                <h3 class="title" style="margin-bottom: 1rem;"><?= htmlspecialchars($pagamento['observacoes'] ?? 'Mensalidade/Parcela') ?></h3>
                <p>Valor: R$ <?= number_format($pagamento['valor'], 2, ',', '.') ?></p>
                <p>Vencimento: <?= date('d/m/Y', strtotime($pagamento['data_vencimento'])) ?></p>
                <p>Status: <span style="color: <?= ($pagamento['status_pagamento'] === 'pago') ? 'green' : 'red'; ?>;">
                    <?= ucfirst(htmlspecialchars($pagamento['status_pagamento'])) ?>
                </span></p>
                <?php if ($pagamento['status_pagamento'] === 'pago' && !empty($pagamento['data_pagamento'])): ?>
                    <p>Data Pagamento: <?= date('d/m/Y', strtotime($pagamento['data_pagamento'])) ?></p>
                    <p>Valor Pago: R$ <?= number_format($pagamento['valor_pago'], 2, ',', '.') ?></p>
                <?php endif; ?>
                <?php if ($pagamento['multa'] > 0): ?>
                    <p style="color: red;">Multa: R$ <?= number_format($pagamento['multa'], 2, ',', '.') ?></p>
                <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p style="text-align:center; color:gray; font-size: 16px; margin-top: 2rem;">Aguardando seleção ou dados.</p>
        <?php endif; ?>
    </section>

<script src="/conexus_sistema/public/js/script.js"></script>
</body>
</html>