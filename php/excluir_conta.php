<?php
// Inclui a conexão com o banco de dados MySQL
include('conexao.php');

// Inicia a sessão para obter o ID do usuário logado
session_start();

// Caso o usuário não esteja logado, redireciona para a página de login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../pages/login.php");
    exit();
}

// Armazena o ID do usuário ativo
$id = $_SESSION['id_usuario'];

// Prepara a query SQL de remoção usando Prepared Statements contra SQL Injection
$stmt = $conexao->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id);

// Se a exclusão der certo, destrói a sessão e redireciona para a página principal
if ($stmt->execute()) {
    session_destroy();
    header("Location: ../../public/index.php");
    exit();
} else {
    echo "Erro ao excluir conta: " . $conexao->error;
}

// Fecha os recursos de banco abertos
$stmt->close();
$conexao->close();
?>