<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['id_usuario']) || empty($_SESSION['carrinho'])) {
    header("Location: ../pages/catalogo.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_endereco = intval($_POST['id_endereco'] ?? 0);

if ($id_endereco <= 0) {
    header("Location: ../pages/checkout.php?erro=endereco_invalido");
    exit();
}

// Segurança extra: Verifica se este endereço realmente pertence ao usuário logado
$sql_check = "SELECT id_endereco FROM enderecos WHERE id_endereco = ? AND id_usuario = ?";
$stmt_check = $conexao->prepare($sql_check);
$stmt_check->bind_param("ii", $id_endereco, $id_usuario);
$stmt_check->execute();
if ($stmt_check->get_result()->num_rows === 0) {
    die("Erro de segurança: Endereço inválido ou não pertence a este usuário.");
}
$stmt_check->close();

// Captura a forma de pagamento enviada pelo checkout
$forma_pagamento = trim($_POST['forma_pagamento'] ?? '');

if (empty($forma_pagamento)) {
    header("Location: ../pages/checkout.php?erro=pagamento_invalido");
    exit();
}

// REGRA: Se for PIX, o status já entra como 'pago', caso contrário 'pendente'
$status_pedido = ($forma_pagamento === 'pix') ? 'pago' : 'pendente';

$ids_limpos = array_map('intval', array_keys($_SESSION['carrinho']));
$ids = implode(',', $ids_limpos);

// 1. Busca os preços e o estoque atual de cada livro no banco
$res = $conexao->query("SELECT id_livro, preco, estoque, titulo FROM livros WHERE id_livro IN ($ids)");

$total_pedido = 0;
$dados_livros = [];

while ($f = $res->fetch_assoc()) {
    $id_f = $f['id_livro'];
    $qtd_desejada = $_SESSION['carrinho'][$id_f];

    // Validação de segurança: Verifica se tem estoque suficiente
    if ($f['estoque'] < $qtd_desejada) {
        header("Location: ../pages/checkout.php?erro=estoque_insuficiente&livro=" . urlencode($f['titulo']));
        exit();
    }

    $dados_livros[$id_f] = [
        'preco' => $f['preco'],
        'estoque_atual' => $f['estoque']
    ];
    $total_pedido += $f['preco'] * $qtd_desejada;
}

// 2. Cria o Pedido
$sql_ped = "INSERT INTO pedidos (id_usuario, id_endereco, valor_total, status) VALUES (?, ?, ?, ?)";
$stmt_ped = $conexao->prepare($sql_ped);
$stmt_ped->bind_param("iids", $id_usuario, $id_endereco, $total_pedido, $status_pedido);
$stmt_ped->execute();
$id_pedido = $stmt_ped->insert_id;
$stmt_ped->close();

// 3. Insere os Itens do Pedido E Atualiza o Estoque
$sql_item = "INSERT INTO itens_pedido (id_pedido, id_livro, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
$stmt_item = $conexao->prepare($sql_item);

$sql_estoque = "UPDATE livros SET estoque = estoque - ? WHERE id_livro = ?";
$stmt_estoque = $conexao->prepare($sql_estoque);

foreach ($_SESSION['carrinho'] as $id_livro => $qtd) {
    $preco_u = $dados_livros[$id_livro]['preco'];
    
    // Insere o item na tabela itens_pedido
    $stmt_item->bind_param("iiid", $id_pedido, $id_livro, $qtd, $preco_u);
    $stmt_item->execute();

    // Desconta a quantidade comprada do estoque do livro
    $stmt_estoque->bind_param("ii", $qtd, $id_livro);
    $stmt_estoque->execute();
}

$stmt_item->close();
$stmt_estoque->close();

// Limpa o carrinho
$_SESSION['carrinho'] = [];

header("Location: ../pages/painel.php?sucesso=pedido_concluido");
exit();
?>