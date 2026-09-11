<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../php/conexao.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: catalogo.php");
    exit();
}

$id_livro = (int) $_GET['id'];

$sql = "SELECT * FROM livros WHERE id_livro = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_livro);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: catalogo.php");
    exit();
}

$livro = $resultado->fetch_assoc();
$stmt->close();

$id_usuario_sessao = $_SESSION['id_usuario'] ?? null;
$id_usuario_logado = $id_usuario_sessao ?? 0;

$sql_comentarios = "SELECT c.id_comentario, c.id_usuario, c.comentario, c.data_comentario, c.status, u.nome,
                    COUNT(DISTINCT lk.id_curtida) AS total_likes,
                    EXISTS(SELECT 1 FROM curtidas_comentarios WHERE id_comentario = c.id_comentario AND id_usuario = ?) AS curtiu
                    FROM comentarios c
                    INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                    LEFT JOIN curtidas_comentarios lk ON c.id_comentario = lk.id_comentario
                    WHERE c.id_livro = ?
                    GROUP BY c.id_comentario
                    ORDER BY c.data_comentario DESC";

$stmt_c = $conexao->prepare($sql_comentarios);
$stmt_c->bind_param("ii", $id_usuario_logado, $id_livro);
$stmt_c->execute();
$resultado_comentarios = $stmt_c->get_result();

$ja_favoritou = false;
if ($id_usuario_sessao) {
    $sql_fav = "SELECT 1 FROM favoritos WHERE id_usuario = ? AND id_livro = ?";
    $stmt_fav = $conexao->prepare($sql_fav);
    $stmt_fav->bind_param("ii", $id_usuario_sessao, $id_livro);
    $stmt_fav->execute();
    $ja_favoritou = $stmt_fav->get_result()->num_rows > 0;
    $stmt_fav->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($livro['titulo']); ?> - Almas Literárias </title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="preload">
    <?php include('../php/header.php'); ?>

    <main class="container pagina-livro">
        <a href="catalogo.php" class="btn-voltar">Voltar ao Catálogo</a>

        <div class="detalhes-livro-wrapper">
            <div class="livro-capa-grande">
                <?php if (!empty($livro['capa'])): ?>
                    <img src="../../<?php echo htmlspecialchars($livro['capa']); ?>" alt="Capa de <?php echo htmlspecialchars($livro['titulo']); ?>">
                <?php else: ?>
                    <div class="capa-placeholder">Sem Capa</div>
                <?php endif; ?>
            </div>

            <div class="livro-info-completa">
                <div class="livro-titulo-header">
                    <h1><?php echo htmlspecialchars($livro['titulo']); ?></h1>
                    <?php if ($id_usuario_sessao): ?>
                        <form action="../php/favoritar.php" method="POST" id="favoritar" class="d-inline">
                            <input type="hidden" name="id_livro" value="<?= $livro['id_livro'] ?>">
                            <button type="submit" class="btn-favoritar <?= $ja_favoritou ? 'curtido' : '' ?>" title="<?= $ja_favoritou ? 'Remover dos favoritos' : 'Adicionar aos favoritos' ?>">
                                <img src="../assets/img/icons/heart.png" alt="Favoritar" class="icone-coracao">
                            </button>
                        </form>
                    <?php endif; ?>
                    <span class="badge-classificacao" data-classificacao="<?php echo htmlspecialchars($livro['classificacao_indicativa']); ?>">
                        <?php echo htmlspecialchars($livro['classificacao_indicativa']); ?>
                    </span>
                </div>

                <p class="genero-tag"><?php echo htmlspecialchars($livro['genero']); ?></p>
                
                <div class="bloco-preco-compra">
                    <h2 class="preco-livro">R$ <?= number_format($livro['preco'] ?? 19.90, 2, ',', '.') ?></h2>
                    
                    <!-- Mensagem dinâmica de estoque -->
                    <div class="aviso-estoque-container" style="margin-bottom: 10px;">
                        <?php if ($livro['estoque'] <= 0): ?>
                            <span class="text-danger" style="color: #dc3545; font-weight: bold;">Produto sem estoque</span>
                        <?php elseif ($livro['estoque'] == 1): ?>
                            <span class="text-warning" style="color: #ffc107; font-weight: bold;">Resta 1 unidade</span>
                        <?php elseif ($livro['estoque'] <= 3): ?>
                            <span class="text-warning" style="color: #ffc107; font-weight: bold;">Atenção: restam apenas <?= $livro['estoque'] ?> unidades</span>
                        <?php else: ?>
                            <span class="text-success" style="color: #28a745;">Em estoque</span>
                        <?php endif; ?>
                    </div>

                    <!-- Se houver estoque, mostra os controles e o botão. Se não, exibe apenas o aviso de indisponível. -->
                    <?php if ($livro['estoque'] > 0): ?>
                        <div class="controle-quantidade">
                            <button type="button" id="btn-menos" class="btn-qtd">-</button>
                            <input type="number" id="qtd-livro" value="1" min="1" max="<?= $livro['estoque'] ?>" readonly class="input-qtd">
                            <button type="button" id="btn-mais" class="btn-qtd">+</button>                            
                        </div>
                        <button type="button" onclick="adicionarAoCarrinho(<?= $livro['id_livro'] ?>)" class="botao">
                            Adicionar ao Carrinho
                        </button>
                    <?php else: ?>
                        <div class="livro-indisponivel-aviso">
                            <p style="color: #6c757d; font-style: italic; margin-top: 5px;">Livro indisponível no momento.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="sinopse-bloco">
                    <h3>Sinopse</h3>
                    <p><?php echo nl2br(htmlspecialchars($livro['descricao'])); ?></p>
                </div>

                <div class="ficha-tecnica">
                    <p><b>Autor:</b> <?php echo htmlspecialchars($livro['autor']); ?></p>
                    <p><b>Páginas:</b> <?php echo htmlspecialchars($livro['paginas']); ?> pag</p>
                    <p><b>Sinopse:</b> <?php echo nl2br(htmlspecialchars($livro['sinopse'])); ?></p>
                </div>
            </div>

    <?php 
    $stmt_c->close();
    $conexao->close();
    ?>
    <script src="../assets/js/script.js" defer></script>
</body>
</html>