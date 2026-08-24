<?php
include('conexao.php'); 

# Capturando os campos

$nome = $_POST['nome']; 
$email = $_POST['email']; 
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$nascimento = $_POST['nascimento']; 

if (empty($nome) || empty($email) || empty($senha) || empty($nascimento) || empty($cpf)){ # o empty verifica se os campos estao vazios
    echo "Preencha todos os campos!!";
    exit();
}

# Criptografando a senha

$senha_segura = password_hash($senha, PASSWORD_DEFAULT);

# Criando o comando SQL

$sql = "INSERT INTO usuarios(nome, email, nascimento, senha_segura, cpf) VALUES ('$nome', '$email', '$nascimento', '$senha_segura', '$cpf')";

# Executando no banco de dados

$conexao->query($sql);
header("Location: login.php");
exit();

# O dado agora e permanente
?>