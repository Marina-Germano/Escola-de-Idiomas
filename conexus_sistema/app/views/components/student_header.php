    <header class="header">
        <section class="flex">
            <a href="../student/home.php" class="logo"> <img src="../../../public/img/conexus_sem_fundo.png" alt=""> Conexus </a>
            <form action="" method="post" class="search-form">
                <input type="text" name="search_box" placeholder="Pesquisar..." required maxlength="100" id="search_box">
                <button type="submit" class="bi bi-search" name="search_box"></button>
            </form>
            <div class="icons">
                <div id="menu-btn" class="bi bi-list"></div>
                <div id="search-btn" class="bi bi-search"></div>
                <div id="user-btn" class="bi bi-person"></div>
                <div id="toggle-btn" class="bi bi-brightness-high"></div>
            </div>

            <div class="profile">
                <img src="../../../public/img/pic-1-removebg-preview.png" alt="Foto de perfil">
                <h3><?= $fetch_user['nome'] ?? 'Aluno'; ?></h3>
                <span><?= $fetch_user['idaluno'] ?? 'Matrícula N/A'; ?></span>
                <div class="flex-btn">
                    <a href="../components/user_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>Sair</span></a>
                </div>
            </div>

        </section>
    </header>
    
    <div class="side-bar">
        <div id="close-btn">
            <i class="fas fa-times"></i>
        </div>

        <nav class="navbar">
        <a href="../../controllers/homeController.php"><i class="fas fa-home"></i><span>Home</span></a>
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/calendarioController.php"><i class="bi bi-calendar-week"></i><span>Aulas</span></a>
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/materialController.php?acao=listar_aluno"><i class="bi bi-folder-check"></i><span>Materiais</span></a> 
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/avaliacaoController.php"><i class="bi bi-reception-4"></i><span>Boletim</span></a>
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/pagamentoController.php"><i class="bi bi-piggy-bank-fill"></i><span>Financeiro</span></a>
        <a href="/escola-de-idiomas/conexus_sistema/app/controllers/contatoController.php"><i class="fas fa-headset"></i><span>Nos contate</span></a>
    </nav>
    </div>