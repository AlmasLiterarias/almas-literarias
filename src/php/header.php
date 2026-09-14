<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}
$pagina_atual = basename($_SERVER['SCRIPT_NAME']);
$total_carrinho = isset($_SESSION['carrinho'])
    ? array_sum($_SESSION['carrinho'])
    : 0;
?>
<header>
    <nav class="navbar navbar-custom navbar-expand-lg navbar-dark px-3">
        <a class="logolight" href="/almas-literarias/public/index.php">
            <img src="/almas-literarias/src/asset/imagens/logoescurodourada.png" alt="logo do site">
        </a>
        <a class="logodark" href="/almas-literarias/public/index.php">
            <img src="/almas-literarias/src/asset/imagens/logoclaro.png" alt="logo do site">
        </a>

        <div class="container-xl d-lg-none">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div id="navbarNav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/almas-literarias/public/index.php">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="/almas-literarias/public/index.php#lancamentos">Lançamentos</a></li>
                <li class="nav-item"><a class="nav-link" href="/almas-literarias/public/index.php#destaques">Destaques</a></li>
                <li class="nav-item"><a class="nav-link" href="/almas-literarias/public/index.php#contato">Contato</a></li>

                <?php if (isset($_SESSION['id_usuario'])): ?>

                    <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): ?>
                        <li class="nav-item">
                            <a href="/almas-literarias/src/pages/cadastroLivro.php" class="nav-link <?php echo ($pagina_atual === 'cadastroLivro.php') ? 'ativo' : ''; ?>">Cadastrar Livro</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a href="/almas-literarias/src/pages/painel.php" class="nav-link <?php echo ($pagina_atual === 'painel.php') ? 'ativo' : ''; ?>">Meu Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/almas-literarias/src/php/logout.php">Sair</a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a href="/almas-literarias/src/pages/login.php" class="nav-link <?php echo ($pagina_atual === 'login.php') ? 'ativo' : ''; ?>">Entrar</a>
                    </li>
                    <li class="nav-item">
                        <a href="/almas-literarias/src/pages/cadastro.php" class="nav-link <?php echo ($pagina_atual === 'cadastro.php') ? 'ativo' : ''; ?>">Cadastrar-se</a>
                    </li>

                <?php endif; ?>

                <li class="nav-item">
                    <div class="cart-icon nav-link" id="cartBtn" style="cursor: pointer;">
                        <span id="cartCount"><?php echo $total_carrinho; ?> 🛒</span>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <button id="themeToggle" aria-pressed="false" aria-label="Ativar modo escuro">
        <img id="theme-icon" src="/almas-literarias/src/asset/imagens/Jerlucitchau.png" alt="Modo escuro">
    </button>
</header>
