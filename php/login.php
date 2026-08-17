<?php
include('conexao.php');
session_start();
#capturando dados
$email = $_POST['email'];
$senha = $_POST['senha'];
#criando a consulta SQL
$sql = "SELECT * FROM cadastro WHERE email = '$email'";
#Execcutando a busca
$resultado = $conexao->query($sql);
if($resultado->num_rows > 0) {
    //usuario encontarado
    $usuario = $resultado->fetch_assoc();
} else {
    die("E-mail não cadastrado!");
}
if (password_verify($senha, $usuario[$senha_segura])) {
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nome'] = $usuario['nome'];
    //Criando o crachá do usuário
    $_SESSION['usuario'] = $usuario['id'];
    header("Location: ../index.html");
    exit();

} else {
    die("Senha incorreta!");
}
?>