<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../php/conexao.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?msg=Faça login para finalizar a compra");
    exit(); 
}
$id_usuario = $_SESSION['id_usuario'];

if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) === 0) {
    header("Location: index.php?msg=Seu carrinho está vazio");
    exit();
}

$sql_enderecos = "SELECT * FROM enderecos WHERE id_usuario = ?";
$stmt_end = $conexao->prepare($sql_enderecos);
$stmt_end->bind_param("i", $id_usuario);
$stmt_end->execute();
$res_enderecos = $stmt_end->get_result();
$enderecos = [];
while ($row = $res_enderecos->fetch_assoc()) {
    $enderecos[] = $row;
}
$stmt_end->close();

$ids_limpos = array_map('intval', array_keys($_SESSION['carrinho']));
$ids = implode(',', $ids_limpos);

$sql = "SELECT id_livro, titulo, preco, capa FROM livros WHERE id_livro IN ($ids)";
$resultado = $conexao->query($sql);

$subtotal = 0;
$itens = [];

while ($f = $resultado->fetch_assoc()) {
    $qtd = $_SESSION['carrinho'][$f['id_livro']];
    $total_item = $f['preco'] * $qtd;
    $subtotal += $total_item;
    $f['quantidade'] = $qtd;
    $f['subtotal'] = $total_item;
    $itens[] = $f;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Almas Literárias</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="preload">
    <?php include('../php/header.php'); ?>

    <main class="container">
        <h1>Finalizar Pedido</h1>

        <div class="checkout-wrapper">
            <form action="../php/finalizar_compra.php" method="POST" class="checkout-form">
                
                <h3>Onde você quer receber seu pedido?</h3>
                <br>
                
                <?php if (count($enderecos) > 0): ?>
                    <div class="enderecos-lista">
                        <?php foreach ($enderecos as $index => $end): ?>
                            <label class="endereco-card-selecao">
                                <input type="radio" name="id_endereco" value="<?= $end['id_endereco'] ?>" <?= $index === 0 ? 'checked' : '' ?> required>
                                <div>
                                    <strong><?= htmlspecialchars($end['apelido_local'] ?? 'Endereço') ?></strong><br>
                                    <?= htmlspecialchars($end['rua']) ?>, <?= htmlspecialchars($end['numero']) ?> - <?= htmlspecialchars($end['bairro']) ?><br>
                                    <?= htmlspecialchars($end['cidade']) ?>/<?= htmlspecialchars($end['estado']) ?> - CEP: <?= htmlspecialchars($end['cep']) ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <a href="painel.php" class="link btn-adicionar-endereco">+ Adicionar um novo endereço no Painel</a>
                <?php else: ?>
                    <div class="alerta-sem-endereco">
                        <p class="alerta-texto-destaque">Você precisa cadastrar um endereço para continuar.</p>
                        <a href="painel.php" class="botao">Ir para o Painel</a>
                    </div>
                <?php endif; ?>

                <hr class="divisor">
                
                <h3>Forma de Pagamento</h3>
                <br>
                <div class="campo">
                    <select name="forma_pagamento" required class="select-largo">
                        <option value="">Selecione...</option>
                        <option value="pix">Pix (Aprovação Imediata)</option>
                        <option value="cartao">Cartão de Crédito</option>
                        <option value="boleto">Boleto Bancário</option>
                    </select>
                </div>

                <button type="submit" class="btn-salvar botao btn-pagamento" <?= count($enderecos) === 0 ? 'disabled' : '' ?>>
                    Confirmar e Pagar
                </button>
            </form>

            <div class="resumo-caixa">
                <h3 class="resumo-titulo">Resumo da Compra</h3>
                <ul class="resumo-lista">
                    <?php foreach ($itens as $item): ?>
                        <li class="resumo-item">
                            <span class="texto-secundario"><?= htmlspecialchars($item['titulo']) ?> <strong class="texto-primario">(x<?= $item['quantidade'] ?>)</strong></span>
                            <strong class="texto-primario">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="resumo-total">
                    <span>Total a pagar:</span>
                    <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </main>
</body>
</html>