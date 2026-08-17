<?php
include('conexao.php');
session_start();

// Capturando dados do formulário
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Uso de Prepared Statement para evitar SQL Injection
$stmt = $conexao->prepare("SELECT id_usuario, nome, senha_segura FROM cadastro WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
} else {
    die("E-mail não cadastrado!");
}
// CORREÇÃO: Substitua 'senha' pelo nome exato da coluna da senha no seu banco de dados (ex: 'senha' ou 'senha_hash')
$coluna_senha_banco = 'senha_segura'; 

if (password_verify($senha, $usuario[$coluna_senha_banco])) {
    // Definindo variáveis de sessão
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['usuario'] = $usuario['id_usuario'];

    header("Location: ../index.html");
    exit();
} else {
    die("Senha incorreta!");
}
?>