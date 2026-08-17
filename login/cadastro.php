<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Cadastrar</title>
    <link rel="stylesheet" href="./CSS/cadastro.css">
</head>

<body>
    <main class="form_container"> <!-- main com a classe "form_container" -->
        <h1>Criar conta</h1>
        <form action="../php/cadastro.php" id="formCadastro" method="POST">
            <div id="input_container">
                <!--Primeiro nome-->
                <div class="nome">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" placeholder="Nome">
                </div>
                <!--CPF-->
                <div class="cpf">
                    <label for="cpf">cpf</label>
                    <input type="number" name="cpf" id="cpf" placeholder="Digite seu CPF">
                </div>
                <!-- E-mail -->
                <div class="email">
                    <label for="email">E-mail</label>
                    <input type="text" name="email" id="email" placeholder="E-mail">
                </div>
                <div class="nascimento">
                    <label for="nascimento">nascimento</label>
                    <input type="date" name="nascimento" id="nascimento" placeholder="Data de nascimento">
                <div class="senha">
                    <label for="senha">Senha </label>
                    <input type="password" name="senha" id="senha1" placeholder="Insira sua senha">
                </div>
                <div class="termos">
                    <input type="checkbox" name="" id="termos">
                    <a href="./Termos aceite.html"> Estou ciente e CONCORDO com os termos </a>
                </div>
                <button type="submit">Cadastrar</button>
                <a href="./login.html">Voltar ao Login</a>
            </div>
        </form>
    </main>
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2025 AlmasLiterárias - Todos os direitos reservados</p>
        <div>
            <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
        </div>
        <script src="./JS/script.js"></script>
</body>

</html>