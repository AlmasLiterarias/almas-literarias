<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) || !isset($_POST['id_livro'])) {
    header('Location: ../pages/login.php');
    exit();
}

$id_usuario = (int) $_SESSION['id_usuario'];
$id_livro = (int) $_POST['id_livro'];

// Checa se o livro já foi favoritado pelo usuário
$sql_check = "SELECT id_favorito FROM favoritos WHERE id_usuario = ? AND id_livro = ?";
$stmt_check = $conexao->prepare($sql_check);
$stmt_check->bind_param("ii", $id_usuario, $id_livro);
$stmt_check->execute();
$res = $stmt_check->get_result();

if ($res->num_rows > 0) {
    // Se já existe, remove dos favoritos
    $sql_del = "DELETE FROM favoritos WHERE id_usuario = ? AND id_livro = ?";
    $stmt_del = $conexao->prepare($sql_del);
    $stmt_del->bind_param("ii", $id_usuario, $id_livro);
    $stmt_del->execute();
    $stmt_del->close();
} else {
    // Se não existe, insere nos favoritos
    $sql_add = "INSERT INTO favoritos (id_usuario, id_livro) VALUES (?, ?)";
    $stmt_add = $conexao->prepare($sql_add);
    $stmt_add->bind_param("ii", $id_usuario, $id_livro);
    $stmt_add->execute();
    $stmt_add->close();
}

$stmt_check->close();
$conexao->close();

// Redireciona de volta para a página anterior
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../pages/catalogo.php'));
exit();