<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$dados = json_decode(file_get_contents('php://input'), true);

if (!is_array($dados)) {
    echo json_encode(['sucesso' => false]);
    exit();
}

$carrinho = [];
foreach ($dados as $item) {
    $id = intval($item['id'] ?? 0);
    $qtd = intval($item['quantity'] ?? 0);
    if ($id > 0 && $qtd > 0) {
        $carrinho[$id] = ($carrinho[$id] ?? 0) + $qtd;
    }
}

$_SESSION['carrinho'] = $carrinho;

echo json_encode(['sucesso' => true]);