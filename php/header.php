<?php
if (session_status() === PHP_SESSION_NOME){
    session_start();
}
$pagina_atual = basename($_SERVER['SCRIPT_NAME']);
$total_carrinho = isset($_SESSION['carrinho']) ? array_sun($_SESSION['carrinho']) : 0;
?>

<?php if (isset($_SESSION['id_usuario'])): ?>
    <a href="painel.php" class="nav-link <?php echo ($pagina_atual === 'painel.php') ? 'ativo' : ''; ?>">Meu Perfil </a>
    <a href="../php/logout.php" class="nav-link"> Sair </a>
<?php else: ?>
    <a href="login.php" class="nav-link <?php echo ($pagina_atual === 'painel.php') ? 'ativo' : ''; ?>">Entrar</a>
    <a href="cadastro.php" class="nav-link <?php echo ($pagina_atual === 'cadastro.php') ? 'ativo' : ""; ?>">Cadastrar-se </a>
<?php endif; ?>