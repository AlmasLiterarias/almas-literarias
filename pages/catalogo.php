<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Catálogo de Livros</title>
=======
    <title>Catálogo de Livro</title>
>>>>>>> 30b846226fd0fb4afafe077e8b1c62c716bdd643
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="preload">
    <?php include('../php/header.php'); ?>
    <main class="container">
        <header class="header-catalogo">
<<<<<<< HEAD
            <h1>Catálogo de Livros</h1>
=======
            <h1>Catálogo de Livro</h1>
>>>>>>> 30b846226fd0fb4afafe077e8b1c62c716bdd643
            <div class="busca-livro">
                <form method="GET" action="../pages/busca.php">
                    <select name="categoria" id="categoria">
                        <option value="" <?= empty($categoria) ? 'selected' : '' ?>>Todas</option>
<<<<<<< HEAD
                        <option value="Romance" <?= (isset($categoria) && $categoria === 'Romance') ? 'selected' : '' ?>>Romance</option>
=======
                        <option value="Drama" <?= (isset($categoria) && $categoria === 'Drama') ? 'selected' : '' ?>>Drama</option>
>>>>>>> 30b846226fd0fb4afafe077e8b1c62c716bdd643
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
        include("../php/conexao.php");
        $sql = "SELECT id_livro, titulo, capa, classificacao_indicativa FROM livros";
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
                <a href="livro.php?id=<?php echo $livro['id_livro']; ?>" class="card-link">
                    <article class="card">
                        <div class="card-capa">
                            <?php if (!empty($livro['capa'])): ?>
                                <img src="../../<?php echo htmlspecialchars($livro['capa']); ?>" alt="Capa">
                            <?php else: ?>
                                <div class="capa-placeholder">Sem Capa</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-header">
                            <h2><?php echo htmlspecialchars($livro['titulo']); ?></h2>
                            <div class="badge-classificacao" data-classificacao="<?php echo htmlspecialchars($livro['classificacao_indicativa']); ?>">
                                <span class="classificacao">
                                    <?php echo htmlspecialchars($livro['classificacao_indicativa']); ?>
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
<<<<<<< HEAD
            <a href="cadastroLivro.php" class="botao">+ Cadastrar Novo Livro</a>
=======
            <a href="cadastrolivro.php" class="botao">+ Cadastrar Novo livro</a>
>>>>>>> 30b846226fd0fb4afafe077e8b1c62c716bdd643
        <?php endif; ?>
    </main>    
    <?php include('../php/footer.php'); ?>
    <script src="../assets/js/script.js" defer></script>
</body>
</html>