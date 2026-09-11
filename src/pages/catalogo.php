<?php
// Inicia a sessão para permitir a verificação das credenciais no cabeçalho
session_start();
$pagina_atual = basename($_SERVER['SCRIPT_NAME']);

include('../php/conexao.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Livros</title>
    <link rel="stylesheet" href="../asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/css/style.css">
</head>
<body class="preload">
    <header>
        <nav class="navbar navbar-custom navbar-expand-lg navbar-dark px-3">
            <a class="logolight" href="../public/index.php">
                <img src="../src/asset/imagens/logoescurodourada.png" alt="logo do site">
            </a>
            <a class="logodark" href="../public/index.php">
                <img src="../src/asset/imagens/logoclaro.png" alt="logo do site">
            </a>
            
            <div class="container-xl d-lg-none">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            
            <div id="navbarNav" class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="../public/index.php">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="#lancamentos">Lançamentos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#destaques">Destaques</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contato">Contato</a></li>

                    <?php if (isset($_SESSION['id_usuario'])): ?> 
                        <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): ?>
                            <li class="nav-item">
                                <a href="../src/pages/cadastroLivro.php" class="nav-link <?php echo (isset($pagina_atual) && $pagina_atual === 'cadastroLivro.php') ? 'ativo' : ''; ?>">Cadastrar Livro</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a href="../src/pages/painel.php" class="nav-link <?php echo (isset($pagina_atual) && $pagina_atual === 'painel.php') ? 'ativo' : ''; ?>">Meu Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../src/php/logout.php">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">        
                            <a href="../src/pages/login.php" class="nav-link <?php echo (isset($pagina_atual) && $pagina_atual === 'login.php') ? 'ativo' : ''; ?>">Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a href="../src/pages/cadastro.php" class="nav-link <?php echo (isset($pagina_atual) && $pagina_atual === 'cadastro.php') ? 'ativo' : ''; ?>">Cadastrar-se</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <div class="cart-icon nav-link" id="cartBtn" style="cursor: pointer;">
                            <span id="cartCount">0 🛒</span>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        
        <button id="themeToggle" aria-pressed="false" aria-label="Ativar modo escuro">
            <img id="theme-icon" src="../src/asset/imagens/Jerlucitchau.png" alt="Modo escuro">
        </button>
    </header>
    <main class="container">
        <header class="header-catalogo">
            <div class="busca-livro">
                <form method="GET" action="../pages/busca.php">
                    <select name="categoria" id="categoria">
                        <option value="" <?= empty($categoria) ? 'selected' : '' ?>>Todas</option>
                        <option value="Romance" <?= (isset($categoria) && $categoria === 'Romance') ? 'selected' : '' ?>>Romance</option>
                        <option value="Ação" <?= (isset($categoria) && $categoria === 'Ação') ? 'selected' : '' ?>>Ação</option>
                        <option value="Ficção Científica" <?= (isset($categoria) && $categoria === 'Ficção Científica') ? 'selected' : '' ?>>Ficção Científica</option>
                    </select>            
                    <input type="text" name="pesquisa" placeholder="Pesquisa..." value="<?= htmlspecialchars($pesquisa ?? '') ?>">            
                    <button type="submit">
                        <img src="../assets/img/icons/lupa.png" alt="Buscar">
                    </button>
                </form>
            </div>
        </header>

        <section id="livros">
        <?php
        // Consulta ajustada para usar a tabela 'produtos' e suas colunas corretas
        $sql = "SELECT id_produto, nome_produto, img_produto, classificacao_indicativa_produto FROM produtos";
        $resultado = $conexao->query($sql);

        if (!$resultado) {
            die("Erro na consulta: " . $conexao->error);
        }

        if ($resultado->num_rows == 0) {
            echo '<p class="nadaEncontrado">Nenhum livro cadastrado no momento.</p>';
        } else {
            while ($livro = $resultado->fetch_assoc()) {
        ?>
                <!-- Card redirecionando para a página individual do livro -->
                <a href="catalogo.php?id=<?php echo $livro['id_produto']; ?>" class="card-link">
                    <article class="card">
                        <div class="card-capa">
                            <?php if (!empty($livro['img_produto'])): ?>
                                <!-- Ajustado o caminho da imagem relativo à pasta src/pages -->
                                <img src="../../<?php echo htmlspecialchars($livro['img_produto']); ?>" alt="Capa">
                            <?php else: ?>
                                <div class="capa-placeholder">Sem Capa</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-header">
                            <h2><?php echo htmlspecialchars($livro['nome_produto']); ?></h2>
                            <div class="badge-classificacao" data-classificacao="<?php echo htmlspecialchars($livro['classificacao_indicativa_produto']); ?>">
                                <span class="classificacao">
                                    <?php echo htmlspecialchars($livro['classificacao_indicativa_produto']); ?>
                                </span>
                            </div>
                        </div>
                    </article>
                </a>
        <?php
            }
        }
        $resultado->free();
        $conexao->close();
        ?>
        </section>

        <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): ?>
            <a href="cadastroLivro.php" class="botao">+ Cadastrar Novo Livro</a>
        <?php endif; ?>
    </main>    
    <<hr>
    
    <div class="cart-modal" id="cartModal">
        <div class="cart-content">
            <h2>Seu carrinho</h2>
            <ul id="cartItems"></ul>
            <p class="cart-total">Total: <span id="cartTotal">R$0,00</span></p>
            <button id="closeCart">Fechar</button>
            <button id="checkout">Finalizar compra</button>
        </div>
    </div>

    <section id="contato">
        <div class="infos-contato">
            <h2>Contato</h2>
            <div class="contatoInfos">
                <div class="jerluci">
                    <img src="../src/asset/imagens/Jerlucitchau.png" alt="Mascote Jerluci">
                </div>
                <ul>
                    <li>(99) 9 9999-9999</li>
                    <li>AlmasLiterarias@gmail.com</li>
                    <li>Brazlândia, DF</li>
                    <div class="icones">
                        <img src="../src/asset/imagens/facebook.png" alt="Facebook">
                        <img src="../src/asset/imagens/instagram.png" alt="Instagram">
                        <img src="../src/asset/imagens/linkedin.png" alt="LinkedIn">
                    </div>
                </ul>
            </div>
        </div>
        <form class="formulario">
            <label>Nome</label>
            <input type="text" placeholder="Insira seu nome">
            <label>Email</label>
            <input type="email" placeholder="Insira seu email">
            <textarea placeholder="Mensagem"></textarea>
            <input class="btn" type="submit" value="ENVIAR">
        </form>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2025 AlmasLiterárias - Todos os direitos reservados</p>
        <div>
            <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
        </div>
    </footer>

    <script src="../asset/js/bootstrap.bundle.min.js"></script>
    <script src="../asset/js/script.js"></script>
</body>
</html>