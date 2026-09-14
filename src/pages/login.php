<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/almas-literarias/src/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="/almas-literarias/src/asset/css/style.css">
</head>

<body>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/almas-literarias/src/php/header.php'); ?>

    <main class="form_container">
        <h1>Login</h1>

        <form action="../php/login.php" method="post">
            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@gmail.com" required>
            </div>
            
            <div class="campo">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="********" required>
            </div>

            <a href="#" class="esqueci-senha">Esqueci minha senha</a>
            <button type="submit" class="btn-cadastrar">Entrar</button>
        </form>

        <div class="form_cadastro">
            <p>Ainda não tem conta? <a href="./cadastro.php">Clique aqui</a></p>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2025 AlmasLiterárias - Todos os direitos reservados</p>
        <div>
            <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
        </div>
    </footer>

    <script src="/almas-literarias/src/asset/js/bootstrap.bundle.min.js"></script>
    <script src="/almas-literarias/src/asset/js/script.js" defer></script>
</body>

</html>