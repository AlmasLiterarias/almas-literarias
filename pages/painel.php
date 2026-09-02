<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Painel do Usuário </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container"> Painel do Usuário </div>
    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="#"> Perfil </a></li>
                <li><a href="#"> Meus Preferidos </a></li>
                <li><a href="#"> Minhas Compras </a></li>
                <li><a href="../php/logout.php"></a> Sair </a></li>
            </ul>
    </aside>
    </nav>

    <main class="content">
        <header class="painel">
            <div>
                <span class="painelUsuario"><strong>Meu Perfil </strong></span>
                <h1>Olá</h1>
            </div>
        </header>
        <section id="meu-perfil" class="content-section active">
            <h2> Meu Perfil </h2>
            <form action="../php/dashbord.php" method="POST">

                <label for="nome"> Nome Completo </label>
                <input type="text" id="nome" placeholder="Nome" required>

                <label for="e-mail"> E-mail </label>
                <input type="email" id="e-mail" placeholder="E-mail" required>

                <label for="endereco"> Endereço </label>
                <input type="endereco" id="endereco" placeholder="Endereço" required>

                <label for="telefone"> Telefone / WhatsApp </label>
                <input type="text" id="telefone" placeholder="Telefone">

                <button type="submit"> Salvar Alterações </button>
            </form>
        </section>
    </main>
    <script src="script.js"></script>
</body>

</html>