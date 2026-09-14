/*<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('conexao.php');

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario']) || !isset($_POST['id_livro'])) {
    echo json_encode(["erro" => "Não autorizado"]);
    exit();
}

$id_usuario = (int) $_SESSION['id_usuario'];
$id_livro = (int) $_POST['id_livro'];

$sql_check = "SELECT id_favorito FROM favoritos WHERE id_usuario = ? AND id_produto = ?";
$stmt_check = $conexao->prepare($sql_check);
$stmt_check->bind_param("ii", $id_usuario, $id_livro);
$stmt_check->execute();
$res = $stmt_check->get_result();

if ($res->num_rows > 0) {
    $sql_del = "DELETE FROM favoritos WHERE id_usuario = ? AND id_produto = ?";
    $stmt_del = $conexao->prepare($sql_del);
    $stmt_del->bind_param("ii", $id_usuario, $id_livro);
    $stmt_del->execute();
    $stmt_del->close();

    echo json_encode(["status" => "removido"]);
} else {
    $sql_add = "INSERT INTO favoritos (id_usuario, id_produto) VALUES (?, ?)";
    $stmt_add = $conexao->prepare($sql_add);
    $stmt_add->bind_param("ii", $id_usuario, $id_livro);
    $stmt_add->execute();
    $stmt_add->close();

    echo json_encode(["status" => "adicionado"]);
}

$stmt_check->close();
$conexao->close();*/