<?php
session_start();
include('conexao.php');

// Verifica se é moderador/admin
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../pages/login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$sql_user = "SELECT tipo_usuario FROM usuarios WHERE id_usuario = ?";
$stmt_u = $conexao->prepare($sql_user);
$stmt_u->bind_param("i", $id_usuario);
$stmt_u->execute();
$res_u = $stmt_u->get_result()->fetch_assoc();
$stmt_u->close();

if (!in_array($res_u['tipo_usuario'], ['moderador', 'admin'])) {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_livro = intval($_POST['id_livro'] ?? 0);
    $estoque = intval($_POST['estoque'] ?? 0);

    if ($id_livro > 0 && $estoque >= 0) {
        $sql = "UPDATE livros SET estoque = ? WHERE id_livro = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $estoque, $id_livro);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: ../pages/painel.php?sucesso=estoque_atualizado");
exit();
?>