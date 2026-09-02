<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('conexao.php');

header('Content-Type: application/json');

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$acao = $_POST['acao'] ?? '';
$id_livro = (int)($_POST['id_livro'] ?? 0);
$quantidade = max(1, (int)($_POST['quantidade'] ?? 1));

if ($acao === 'adicionar' && $id_livro > 0) {
    // Consulta o estoque atual no banco de dados para segurança
    $stmt_est = $conexao->prepare("SELECT estoque FROM livros WHERE id_livro = ?");
    $stmt_est->bind_param("i", $id_livro);
    $stmt_est->execute();
    $res_est = $stmt_est->get_result()->fetch_assoc();
    $estoque_disponivel = $res_est['estoque'] ?? 0;
    $stmt_est->close();

    $quantidade_atual = $_SESSION['carrinho'][$id_livro] ?? 0;
    $nova_quantidade = $quantidade_atual + $quantidade;

    if ($nova_quantidade <= $estoque_disponivel) {
        $_SESSION['carrinho'][$id_livro] = $nova_quantidade;
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Quantidade indisponível em estoque.']);
        exit();
    }
} elseif ($acao === 'atualizar' && $id_livro > 0) {
    // Consulta o estoque atual para validar a alteração manual de quantidade
    $stmt_est = $conexao->prepare("SELECT estoque FROM livros WHERE id_livro = ?");
    $stmt_est->bind_param("i", $id_livro);
    $stmt_est->execute();
    $res_est = $stmt_est->get_result()->fetch_assoc();
    $estoque_disponivel = $res_est['estoque'] ?? 0;
    $stmt_est->close();

    if ($quantidade <= $estoque_disponivel) {
        $_SESSION['carrinho'][$id_livro] = $quantidade;
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Quantidade excede o estoque disponível.']);
        exit();
    }
} elseif ($acao === 'remover' && $id_livro > 0) {
    unset($_SESSION['carrinho'][$id_livro]);
} elseif ($acao === 'limpar') {
    $_SESSION['carrinho'] = [];
}

// Recalcula totais do carrinho
$total_itens = array_sum($_SESSION['carrinho']);
$subtotal = 0;
$itens_detalhados = [];

if (!empty($_SESSION['carrinho'])) {
    $ids = implode(',', array_keys($_SESSION['carrinho']));
    $sql = "SELECT id_livro, titulo, preco, capa FROM livros WHERE id_livro IN ($ids)";
    $res = $conexao->query($sql);
    
    while ($f = $res->fetch_assoc()) {
        $qtd = $_SESSION['carrinho'][$f['id_livro']];
        $total_item = $f['preco'] * $qtd;
        $subtotal += $total_item;
        
        $itens_detalhados[] = [
            'id_livro' => $f['id_livro'],
            'titulo' => $f['titulo'],
            'preco' => number_format($f['preco'], 2, ',', '.'),
            'capa' => $f['capa'],
            'quantidade' => $qtd,
            'subtotal' => number_format($total_item, 2, ',', '.')
        ];
    }
}

echo json_encode([
    'sucesso' => true,
    'total_itens' => $total_itens,
    'subtotal' => number_format($subtotal, 2, ',', '.'),
    'itens' => $itens_detalhados
]);
exit();
?>