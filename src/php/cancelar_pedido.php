<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../pages/login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_pedido = intval($_POST['id_pedido'] ?? 0);

if ($id_pedido <= 0) {
    header("Location: ../pages/painel.php?erro=pedido_invalido");
    exit();
}

// Verifica se o pedido pertence ao usuário e qual o status atual
$sql_check = "SELECT id_pedido, status FROM pedidos WHERE id_pedido = ? AND id_usuario = ?";
$stmt_check = $conexao->prepare($sql_check);
$stmt_check->bind_param("ii", $id_pedido, $id_usuario);
$stmt_check->execute();
$resultado = $stmt_check->get_result();

if ($resultado->num_rows === 0) {
    $stmt_check->close();
    header("Location: ../pages/painel.php?erro=pedido_nao_encontrado");
    exit();
}

$pedido = $resultado->fetch_assoc();
$stmt_check->close();

// Só permite cancelar se o status atual for estritamente 'pendente'
if ($pedido['status'] === 'pendente') {
    $sql_cancela = "UPDATE pedidos SET status = 'cancelado' WHERE id_pedido = ?";
    $stmt_cancela = $conexao->prepare($sql_cancela);
    $stmt_cancela->bind_param("i", $id_pedido);
    $stmt_cancela->execute();
    $stmt_cancela->close();

    header("Location: ../pages/painel.php?sucesso=pedido_cancelado");
    exit();
} else {
    header("Location: ../pages/painel.php?erro=nao_pode_cancelar");
    exit();
}
?>