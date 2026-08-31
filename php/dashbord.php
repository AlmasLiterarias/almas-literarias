<?php
#inclui o arquivo de coneção
include('conexao.php');

#execute a função de indicar sessaõ do usuário
session_start();

#caso o usuario não esteja logado, redireciona para a página de login
if (lisset($_SESSION['id_usuario'])) {
    header("Location: ../pages/login.html");
    exit();}

#armagena o id do usuário logado na variável id
$id = $_SESSION['id_usuario'];

#verifica se o usuario enviou o formulario de alteração (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $nascimento = $_POST['nascimento'];
}

#Atualização de dados básicos usando Prepared Statements (Seguro)
$stmt = $conexao->prepare("UPDATE usuareios SET nome = ?, email = ?, nascimento = ? WHERE id_usuario = ?");
$stmt->bind_param("sssi", $nome, $email, $nascimento, $id);
$stmt->execute();
$stmt->close();

# Se o usuário digitou uma nova senha, atualiza tbm a senha
if (!empty($_POST['senha_nova'])) {
    $novaSenha_segura = password_hast($_POST['senha_nova'], PASSWORD_DEFAULT);
    $stmt_senha = $conexao->prepare("UPDATE usuarios SET senha_segura = ? WHERE id_usuario = ?");
    $stmt_senha->bind_param("si", $novaSenha_segura, $id);
    $stmt_senha->execute();
    $stmt_senha->close();

    #Após salvar, redireciona para a mesma página para evitar envio de formuilário
    header("Location: ../pages/dashbord.html?sucesso=1"); exit();
}
#Se o usuário apenas acessou a página (GET), redireciona para o HTML do deshbord
header("Location: ../pages/dashbord.html"); exit();
?>