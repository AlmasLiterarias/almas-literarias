<?php
// Inicia a sessão no topo absoluto do arquivo
session_start();

// Bloqueia quem não está logado ou quem não é administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Livro - Almas Literárias</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="preload">
    <!-- Inclui o cabeçalho dinâmico único -->
    <?php include('../php/header.php'); ?>
    <main class="container">
        <h1>Cadastro de livros</h1>
        <form action="../php/cadastrarFilme.php" method="post" enctype="multipart/form-data" id="formFilme">
            <div class="campo">
                <label for="imagem">Capa do Livro</label>
                <input type="file" id="imagem" name="imagem" accept="image/png, image/jpeg, image/webp" required>
            </div>
            <div class="campo">
                <label for="titulo">Título do Livro</label>
                <input type="text" id="titulo" name="titulo" placeholder="Digite o título do filme">
            </div>
            <div class="campo">
                <label for="sinopse">Sinopse</label>
                <textarea id="sinopse" name="sinopse" placeholder="Digite a sinopse do filme" rows="5"></textarea>
            </div>
            <div class="campo">
                <label>Gêneros</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="genero[]" value="Ação">Ação</label>
                    <label><input type="checkbox" name="genero[]" value="Aventura">Aventura</label>
                    <label><input type="checkbox" name="genero[]" value="Animação">Animação</label>
                    <label><input type="checkbox" name="genero[]" value="Comédia">Comédia</label>
                    <label><input type="checkbox" name="genero[]" value="Crime">Crime</label>
                    <label><input type="checkbox" name="genero[]" value="Drama">Drama</label>
                    <label><input type="checkbox" name="genero[]" value="Fantasia">Fantasia</label>
                    <label><input type="checkbox" name="genero[]" value="Ficção Científica">Ficção Científica</label>
                    <label><input type="checkbox" name="genero[]" value="Terror">Terror</label>
                    <label><input type="checkbox" name="genero[]" value="Suspense">Suspense</label>
                </div>
            </div>
            <div class="campo">
                <label for="classificacao">Classificação indicativa</label>
                <select name="classificacao" id="classificacao">
                    <option value="" selected disabled>Selecione</option>
                    <option value="Livre">Livre</option>
                    <option value="10">10</option>
                    <option value="12">12</option>
                    <option value="14">14</option>
                    <option value="16">16</option>
                    <option value="18">18</option>
                </select>
            </div>
            <div class="campo">
                <label for="autor">Autor</label>
                <input type="text" id="autor" name="autor" placeholder="Digite o nome do autor do filme">
            </div>
            <div class="campo">
                <label for="qnt_paginas">Quantidade de Páginas</label>
                <input type="number" id="qnt_paginas" name="qnt_paginas" min="1" step="1" placeholder="Ex.: 120">
            </div>
            <button type="submit">Cadastrar</button>
        </form>
    </main>    
    <?php include('../php/footer.php') ?>
    <script src="../assets/js/script.js" defer></script>
</body>
</html>