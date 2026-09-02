<?php require_once('../php/busca.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca de Livros</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="preload">
    <?php include('../php/header.php'); ?>
    
    <main class="container">
        <div class="resultado-busca-container">
            
            <!-- BARRA INFORMATIVA DE RESULTADOS -->
            <div class="busca-info-bar">
                <div>
                    <h1 class="busca-titulo">
                        <?php if (!empty($pesquisa)): ?>
                            Resultados para: <span class="termo-pesquisado">"<?= htmlspecialchars($pesquisa) ?>"</span>
                        <?php else: ?>
                            Todos os Livros
                        <?php endif; ?>
                    </h1>
                </div>

                <div class="busca-controles">
                    <span class="contador-resultados">
                        <strong><?= count($livros) ?></strong> livro(s) encontrado(s)
                    </span>

                    <select class="select-ordenacao" name="ordem" onchange="this.form.submit()">
                        <option value="recentes">Mais Recentes</option>
                        <option value="nota">Melhor Avaliados</option>
                        <option value="az">A - Z</option>
                    </select>
                </div>
            </div>

            <!-- LISTAGEM DE livros -->
            <?php if (!empty($livros)): ?>
                <section id="livros">
                    <?php foreach ($livros as $livro): ?>
                        <a href="livro.php?id=<?= $livro['id'] ?? $livro['id_livro'] ?>" class="card-link">
                            <article class="card">
                                <span class="badge-classificacao" data-classificacao="<?= $livro['classificacao'] ?? 'Livre' ?>">
                                    <?= $livro['classificacao'] ?? 'Livre' ?>
                                </span>
                                <div class="card-capa">
                                    <?php if (!empty($livro['capa'])): ?>
                                        <img src="../../<?= $livro['capa'] ?>" alt="<?= htmlspecialchars($livro['titulo']) ?>">
                                    <?php else: ?>
                                        <div class="capa-placeholder">Sem Capa</div>
                                    <?php endif; ?>
                                </div>
                                <span class="genero-tag"><?= htmlspecialchars($livro['categoria'] ?? '') ?></span>
                                <h2><?= htmlspecialchars($livro['titulo']) ?></h2>
                                <p><?= htmlspecialchars($livro['sinopse']) ?></p>
                            </article>
                        </a>
                    <?php endforeach; ?>
                </section>

                <div class="paginacao">
                    <a href="#" class="paginacao-item">&laquo;</a>
                    <a href="#" class="paginacao-item ativo">1</a>
                    <a href="#" class="paginacao-item">&raquo;</a>
                </div>

            <!-- MENSAGEM DE ERRO/SEM RESULTADOS -->
            <?php else: ?>
                <div class="sem-resultados">
                    <svg class="sem-resultados-icone" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="8" y1="11" x2="14" y2="11"></line>
                    </svg>
                    <h2>Nenhum livro encontrado</h2>
                    <p>Não encontramos nenhum título correspondente à sua busca.</p>
                    <a href="index.php" class="botao">Voltar ao Catálogo</a>
                </div>
            <?php endif; ?>

        </div>
    </main>
    
</html>