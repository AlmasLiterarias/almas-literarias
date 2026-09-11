<?php
include('conexao.php');
session_start();

// Capturando dados do formulário
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// 1. Incluída a coluna 'tipo' na busca da tabela 'usuarios'
$stmt = $conexao->prepare("SELECT id_usuario, nome, senha_segura, tipo_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
} else {
    die("E-mail não cadastrado!");
}

$coluna_senha_banco = 'senha_segura'; 

if (password_verify($senha, $usuario[$coluna_senha_banco])) {
    // Definindo variáveis de sessão
    $_SESSION['id_usuario']   = $usuario['id_usuario'];
    $_SESSION['nome']         = $usuario['nome'];
    $_SESSION['usuario']      = $usuario['id_usuario'];
    
    // 2. CORREÇÃO: Salva o tipo do usuário na sessão para o cabeçalho identificar o perfil admin
    // Caso a coluna no seu banco tenha outro nome (ex: tipo_usuario), altere $usuario['tipo'] abaixo:
    $_SESSION['tipo_usuario'] = $usuario['tipo_usuario']; 

    header("Location: ../../public/index.php");
    exit();
} else {
    die("Senha incorreta!");
}
?>