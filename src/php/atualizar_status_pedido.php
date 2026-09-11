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
    $id_pedido = intval($_POST['id_pedido'] ?? 0);
    $novo_status = trim($_POST['status'] ?? '');

    $status_permitidos = ['pendente', 'pago', 'cancelado'];

    if ($id_pedido > 0 && in_array($novo_status, $status_permitidos)) {
        $sql = "UPDATE pedidos SET status = ? WHERE id_pedido = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("si", $novo_status, $id_pedido);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: ../pages/painel.php?sucesso=status_atualizado");
exit();
?>