<?php
// Inicia a sessão para permitir a verificação das credenciais no cabeçalho
session_start();
$pagina_atual = basename($_SERVER['SCRIPT_NAME']);

include_once(__DIR__ . '/../src/php/conexao.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almas Literárias</title>
    <link rel="stylesheet" href="../src/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="../src/asset/css/style.css">
</head>

<body>
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
                        <div class="cart-icon nav-link"  id="cartBtn" style="cursor: pointer;">
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

    <section id="banner">
        <div id="carouselLight" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item align-items-center justify-content-center active">
                    <img src="../src/asset/imagens/bannerclaro1.png" class="d-block w-100" alt="Finanças pessoais">
                </div>
                <div class="carousel-item align-items-center justify-content-center">
                    <img src="../src/asset/imagens/bannerclaro2.png" class="d-block w-100" alt="Investimentos">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselLight" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselLight" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        <div id="carouselDark" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item align-items-center justify-content-center active">
                    <img src="../src/asset/imagens/bannerescuro1.png" class="d-block w-100" alt="Finanças pessoais">
                </div>
                <div class="carousel-item align-items-center justify-content-center">
                    <img src="../src/asset/imagens/bannerescuro2.png" class="d-block w-100" alt="Investimentos">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselDark" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselDark" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <section id="bannerMobile">
        <div id="carouselLight-Mobile" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item align-items-center justify-content-center active">
                    <img src="../src/asset/imagens/bannerclaro1mobile.png" class="d-block w-100" alt="Finanças pessoais">
                </div>
                <div class="carousel-item align-items-center justify-content-center">
                    <img src="../src/asset/imagens/bannerclaro2mobile.png" class="d-block w-100" alt="Investimentos">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselLight-Mobile" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselLight-Mobile" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        <div id="carouselDark-Mobile" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item align-items-center justify-content-center active">
                    <img src="../src/asset/imagens/bannerescuro1mobile.png" class="d-block w-100" alt="Finanças pessoais">
                </div>
                <div class="carousel-item align-items-center justify-content-center">
                    <img src="../src/asset/imagens/bannerescuro2mobile.png" class="d-block w-100" alt="Investimentos">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselDark-Mobile" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselDark-Mobile" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <main>
        <!-- SEÇÃO LANÇAMENTOS -->
        <section id="lancamentos" class="py-5">
            <div class="container">
                <h3 class="title-line">Lançamentos</h3>

                <?php
                $sql = "SELECT id_produto, nome_produto, img_produto, preco_produto, tipo_produto, idioma_produto 
                        FROM produtos ORDER BY id_produto DESC LIMIT 12";
                $resultado = $conexao->query($sql);

                if (!$resultado) {
                    echo '<p class="nadaEncontrado">Erro na consulta de lançamentos.</p>';
                } elseif ($resultado->num_rows == 0) {
                    echo '<p class="nadaEncontrado">Nenhum produto cadastrado no momento.</p>';
                } else {
                    $produtos = [];
                    while ($produto = $resultado->fetch_assoc()) {
                        $produtos[] = $produto;
                    }
                    $produtosDesktop = array_chunk($produtos, 3);
                ?>

                    <!-- CARROSSEL DESKTOP -->
                    <div id="carousel1" class="carousel slide desktop" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($produtosDesktop as $index => $grupoProdutos): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <div class="row justify-content-around">
                                        <?php foreach ($grupoProdutos as $prod): ?>
                                            <div class="card col-md-3" style="width: 18rem;">
                                                <a href="produto.php?id=<?php echo $prod['id_produto']; ?>">
                                                    <?php if (!empty($prod['img_produto'])): ?>
                                                        <img src="../<?php echo htmlspecialchars($prod['img_produto']); ?>" alt="<?php echo htmlspecialchars($prod['nome_produto']); ?>">
                                                    <?php else: ?>
                                                        <div class="capa-placeholder">Sem Imagem</div>
                                                    <?php endif; ?>
                                                </a>
                                                <h5><?php echo htmlspecialchars($prod['nome_produto']); ?></h5>
                                                <p><?php echo htmlspecialchars($prod['tipo_produto']); ?> - edição em <?php echo htmlspecialchars($prod['idioma_produto']); ?></p>
                                                <div class="botao">
                                                    <p><strong>R$ <?php echo number_format($prod['preco_produto'], 2, ',', '.'); ?></strong></p>
                                                    <button class="add-to-cart" data-id="<?php echo $prod['id_produto']; ?>">Comprar</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- CARROSSEL MOBILE -->
                    <div id="carousel1mob" class="carousel slide mobile" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($produtos as $index => $prod): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <div class="row justify-content-center">
                                        <div class="card col-md-3" style="width: 18rem;">
                                            <a href="produto.php?id=<?php echo $prod['id_produto']; ?>">
                                                <?php if (!empty($prod['img_produto'])): ?>
                                                    <img src="../<?php echo htmlspecialchars($prod['img_produto']); ?>" alt="<?php echo htmlspecialchars($prod['nome_produto']); ?>">
                                                <?php else: ?>
                                                    <div class="capa-placeholder">Sem Imagem</div>
                                                <?php endif; ?>
                                            </a>
                                            <h5><?php echo htmlspecialchars($prod['nome_produto']); ?></h5>
                                            <p><?php echo htmlspecialchars($prod['tipo_produto']); ?> - edição em <?php echo htmlspecialchars($prod['idioma_produto']); ?></p>
                                            <div class="botao">
                                                <p><strong>R$ <?php echo number_format($prod['preco_produto'], 2, ',', '.'); ?></strong></p>
                                                <button class="add-to-cart" data-id="<?php echo $prod['id_produto']; ?>">Comprar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button class="carousel-control-prev mobile" type="button" data-bs-target="#carousel1mob" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next mobile" type="button" data-bs-target="#carousel1mob" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    </div>

                <?php } ?>
            </div>
        </section>

        <!-- SEÇÃO DESTAQUES -->
        <section id="destaques" class="py-5">
            <div class="container">
                <h3 class="title-line">Destaques</h3>

                <?php
                $sql = "SELECT id_produto, nome_produto, img_produto, preco_produto, tipo_produto, idioma_produto 
                        FROM produtos 
                        ORDER BY id_produto ASC 
                        LIMIT 12";
                        
                $resultado = $conexao->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    $produtos = [];
                    while ($row = $resultado->fetch_assoc()) {
                        $produtos[] = $row;
                    }
                    $produtosDesktop = array_chunk($produtos, 3);
                ?>

                    <!-- CARROSSEL DESKTOP -->
                    <div id="carousel2" class="carousel slide desktop" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($produtosDesktop as $index => $grupo): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <div class="row justify-content-around">
                                        <?php foreach ($grupo as $prod): ?>
                                            <div class="card col-md-3" style="width: 18rem;">
                                                <a href="produto.php?id=<?php echo $prod['id_produto']; ?>">
                                                    <?php if (!empty($prod['img_produto'])): ?>
                                                        <img src="../<?php echo htmlspecialchars($prod['img_produto']); ?>" alt="<?php echo htmlspecialchars($prod['nome_produto']); ?>">
                                                    <?php else: ?>
                                                        <div class="capa-placeholder">Sem Imagem</div>
                                                    <?php endif; ?>
                                                </a>
                                                <h5><?php echo htmlspecialchars($prod['nome_produto']); ?></h5>
                                                <p><?php echo htmlspecialchars($prod['tipo_produto']); ?> - edição em <?php echo htmlspecialchars($prod['idioma_produto']); ?></p>
                                                <div class="botao">
                                                    <p><strong>R$ <?php echo number_format($prod['preco_produto'], 2, ',', '.'); ?></strong></p>
                                                    <button class="add-to-cart" data-id="<?php echo $prod['id_produto']; ?>">Comprar</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button class="carousel-control-prev desktop" type="button" data-bs-target="#carousel2" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next desktop" type="button" data-bs-target="#carousel2" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    </div>

                    <!-- CARROSSEL MOBILE -->
                    <div id="carousel2mob" class="carousel slide mobile" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($produtos as $index => $prod): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <div class="row justify-content-around">
                                        <div class="card col-md-3" style="width: 18rem;">
                                            <a href="produto.php?id=<?php echo $prod['id_produto']; ?>">
                                                <?php if (!empty($prod['img_produto'])): ?>
                                                    <img src="../<?php echo htmlspecialchars($prod['img_produto']); ?>" alt="<?php echo htmlspecialchars($prod['nome_produto']); ?>">
                                                <?php else: ?>
                                                    <div class="capa-placeholder">Sem Imagem</div>
                                                <?php endif; ?>
                                            </a>
                                            <h5><?php echo htmlspecialchars($prod['nome_produto']); ?></h5>
                                            <p><?php echo htmlspecialchars($prod['tipo_produto']); ?> - edição em <?php echo htmlspecialchars($prod['idioma_produto']); ?></p>
                                            <div class="botao">
                                                <p><strong>R$ <?php echo number_format($prod['preco_produto'], 2, ',', '.'); ?></strong></p>
                                                <button class="add-to-cart" data-id="<?php echo $prod['id_produto']; ?>">Comprar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button class="carousel-control-prev mobile" type="button" data-bs-target="#carousel2mob" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next mobile" type="button" data-bs-target="#carousel2mob" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    </div>

                <?php 
                } else {
                    echo "<p>Nenhum destaque encontrado.</p>";
                }
                ?>
            </div>
        </section>

        <!-- SEÇÃO ROMANCES -->
        <section id="romances" class="py-5">
            <div class="container">
                <h3 class="title-line">Romances Leves e aconchegantes</h3>

                <?php
                $sql = "SELECT id_produto, nome_produto, img_produto, preco_produto, tipo_produto, idioma_produto 
                    FROM produtos 
                    WHERE categoria_produto = 'Romance'
                    ORDER BY id_produto DESC 
                    LIMIT 12";
                        
                $resultado = $conexao->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    $produtos = [];
                    while ($row = $resultado->fetch_assoc()) {
                        $produtos[] = $row;
                    }
                    $produtosDesktop = array_chunk($produtos, 4);
                ?>

                    <!-- CARROSSEL DESKTOP -->
                    <div id="carousel3" class="carousel desktop slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($produtosDesktop as $index => $grupo): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <div class="row justify-content-around">
                                        <?php foreach ($grupo as $prod): ?>
                                            <div class="card col-md-3" style="width: 18rem;">
                                                <a href="produto.php?id=<?php echo $prod['id_produto']; ?>">
                                                    <?php if (!empty($prod['img_produto'])): ?>
                                                        <img src="../<?php echo htmlspecialchars($prod['img_produto']); ?>" alt="<?php echo htmlspecialchars($prod['nome_produto']); ?>">
                                                    <?php else: ?>
                                                        <div class="capa-placeholder">Sem Imagem</div>
                                                    <?php endif; ?>
                                                </a>
                                                <h5><?php echo htmlspecialchars($prod['nome_produto']); ?></h5>
                                                <p><?php echo htmlspecialchars($prod['tipo_produto']); ?> - edição em <?php echo htmlspecialchars($prod['idioma_produto']); ?></p>
                                                <div class="botao">
                                                    <p><strong>R$ <?php echo number_format($prod['preco_produto'], 2, ',', '.'); ?></strong></p>
                                                    <button class="add-to-cart" data-id="<?php echo $prod['id_produto']; ?>">Comprar</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button class="carousel-control-prev desktop" type="button" data-bs-target="#carousel3" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next desktop" type="button" data-bs-target="#carousel3" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    </div>

                    <!-- CARROSSEL MOBILE -->
                    <div id="carousel3mob" class="carousel slide mobile" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($produtos as $index => $prod): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <div class="row justify-content-around">
                                        <div class="card col-md-3" style="width: 18rem;">
                                            <a href="produto.php?id=<?php echo $prod['id_produto']; ?>">
                                                <?php if (!empty($prod['img_produto'])): ?>
                                                    <img src="../<?php echo htmlspecialchars($prod['img_produto']); ?>" alt="<?php echo htmlspecialchars($prod['nome_produto']); ?>">
                                                <?php else: ?>
                                                    <div class="capa-placeholder">Sem Imagem</div>
                                                <?php endif; ?>
                                            </a>
                                            <h5><?php echo htmlspecialchars($prod['nome_produto']); ?></h5>
                                            <p><?php echo htmlspecialchars($prod['tipo_produto']); ?> - edição em <?php echo htmlspecialchars($prod['idioma_produto']); ?></p>
                                            <div class="botao">
                                                <p><strong>R$ <?php echo number_format($prod['preco_produto'], 2, ',', '.'); ?></strong></p>
                                                <button class="add-to-cart" data-id="<?php echo $prod['id_produto']; ?>">Comprar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button class="carousel-control-prev mobile" type="button" data-bs-target="#carousel3mob" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next mobile" type="button" data-bs-target="#carousel3mob" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    </div>

                <?php 
                } else {
                    echo "<p>Nenhum romance encontrado.</p>";
                }
                ?>
            </div>
        </section>
    </main>

    <hr>
    
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

    <script src="../src/asset/js/bootstrap.bundle.min.js"></script>
    <script src="../src/asset/js/script.js"></script>

    <?php 
    // Encerra a conexão com o banco de dados no final da página
    if (isset($conexao) && $conexao instanceof mysqli) {
        $conexao->close();
    }
    ?>
</body>
</html>