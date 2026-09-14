<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário</title>
    <link rel="stylesheet" href="/almas-literarias/src/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="/almas-literarias/src/asset/css/style.css">
</head>

<body>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/almas-literarias/src/php/header.php'); ?>

    <div class="painel-usuario">

        <aside class="sidebar">
            <nav>
                <ul>
                    <li><a href="#">Perfil</a></li>
                    <li><a href="#">Meus Preferidos</a></li>
                    <li><a href="#">Minhas Compras</a></li>
                    <li><a href="/almas-literarias/src/php/logout.php">Sair</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <section id="meu-perfil" class="content-section active">
                <h2>Meu Perfil</h2>
                <form action="/almas-literarias/src/php/dashboard.php" method="POST">

                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome" required>

                    <label for="e-mail">E-mail</label>
                    <input type="email" id="e-mail" name="email" placeholder="E-mail" required>

                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" placeholder="Endereço" required>

                    <label for="telefone">Telefone / WhatsApp</label>
                    <input type="text" id="telefone" name="telefone" placeholder="Telefone">

                    <button type="submit">Salvar Alterações</button>
                </form>
            </section>
        </main>

    </div>

    <script src="/almas-literarias/src/asset/js/bootstrap.bundle.min.js"></script>
    <script src="/almas-literarias/src/asset/js/script.js" defer></script>
</body>

</html>