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

$sql = "SELECT 
    id_produto AS id_livro,
    nome_produto AS titulo,
    img_produto AS capa,
    descricao_produto AS descricao,
    categoria_produto AS genero,
    classificacao_indicativa_produto AS classificacao_indicativa,
    autor_produto AS autor,
    paginas_produto AS paginas,
    preco_produto AS preco,
    quantidade_produto AS estoque,
    pasta_imagens
    FROM produtos
    WHERE id_produto = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_livro);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: catalogo.php");
    exit();
}

/* Pega os dados do livro */
$livro = $resultado->fetch_assoc();

$stmt->close();

/*
 * CARROSSEL DE MINIATURAS
 * Só aparece se TODAS as condições abaixo forem verdadeiras:
 * 1) O produto tem valor preenchido em pasta_imagens (ex: "livro-15")
 * 2) Existe de fato a pasta:
 *    src/asset/livro/imagens/capas-livros-pi/<pasta_imagens>/
 * 3) Dentro dela existem arquivos .jpg, .jpeg, .png ou .webp
 */
$imagensLivro = array();

$nomePasta = trim($livro['pasta_imagens'] ?? '');

if ($nomePasta !== '') {

    $pastaEncontrada = __DIR__ . "/../asset/livro/imagens/capas-livros-pi/" . $nomePasta;
    $urlPastaLivros = "../asset/livro/imagens/capas-livros-pi/" . $nomePasta . "/";

    if (is_dir($pastaEncontrada)) {
        $arquivos = scandir($pastaEncontrada);

        foreach ($arquivos as $arquivo) {
            if ($arquivo === "." || $arquivo === "..") continue;

            $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));

            if (in_array($extensao, ["jpg", "jpeg", "png", "webp"])) {
                $imagensLivro[] = $urlPastaLivros . rawurlencode($arquivo);
            }
        }

        sort($imagensLivro);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo htmlspecialchars($livro['titulo']); ?> - Almas Literárias
    </title>
<link rel="stylesheet" href="/almas-literarias/src/asset/css/bootstrap.min.css">
<link rel="stylesheet" href="/almas-literarias/src/asset/css/style.css">
</head>

<body class="preload">

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/almas-literarias/src/php/header.php'); ?>

    <main class="pagina-livro">

        <a href="catalogo.php" class="btn-voltar">
            Voltar ao Catálogo
        </a>

        <div class="detalhes-livro-wrapper">

            <!-- =========================
                 PARTE DA CAPA
            ========================== -->

            <div class="livro-capa-area">

                <!-- IMAGEM GRANDE -->

                <div class="livro-capa-grande">

                    <?php if (!empty($imagensLivro)): ?>

                    <img id="imagemPrincipal" src="<?php echo htmlspecialchars($imagensLivro[0]); ?>"
                        alt="Capa de <?php echo htmlspecialchars($livro['titulo']); ?>">

                    <?php elseif (!empty($livro['capa'])): ?>

                    <img id="imagemPrincipal" src="../../<?php echo htmlspecialchars($livro['capa']); ?>"
                        alt="Capa de <?php echo htmlspecialchars($livro['titulo']); ?>">

                    <?php else: ?>

                    <div class="capa-placeholder">
                        Sem Capa
                    </div>

                    <?php endif; ?>

                </div>


                <!-- MINIATURAS -->

                <?php if (count($imagensLivro) > 1): ?>

                <div class="miniaturas">

                    <?php foreach ($imagensLivro as $indice => $imagem): ?>

                    <img src="<?php echo htmlspecialchars($imagem); ?>"
                        class="thumb <?php echo $indice === 0 ? 'thumb-ativa' : ''; ?>"
                        onclick="trocarImagem(this.src, this)" alt="Imagem <?php echo $indice + 1; ?>">

                    <?php endforeach; ?>

                </div>

                <?php endif; ?>

            </div>


            <!-- =========================
                 INFORMAÇÕES DO LIVRO
            ========================== -->

            <div class="livro-info-completa">

                <div class="livro-titulo-header">

                    <h1>
                        <?php echo htmlspecialchars($livro['titulo']); ?>
                    </h1>

                    <span class="badge-classificacao"
                        data-classificacao="<?php echo htmlspecialchars($livro['classificacao_indicativa']); ?>">
                        <?php echo htmlspecialchars($livro['classificacao_indicativa']); ?>
                    </span>

                </div>


                <!-- CATEGORIA -->

                <p class="genero-tag">
                    <?php echo htmlspecialchars($livro['genero']); ?>
                </p>


                <!-- PREÇO / CARRINHO -->

                <div class="bloco-preco-compra">

                    <h2 class="preco-livro">
                        R$
                        <?= number_format($livro['preco'] ?? 19.90, 2, ',', '.') ?>
                    </h2>


                    <div class="aviso-estoque-container" style="margin-bottom: 10px;">

                        <?php if ($livro['estoque'] <= 0): ?>

                        <span class="text-danger" style="color: #dc3545; font-weight: bold;">
                            Produto sem estoque
                        </span>

                        <?php elseif ($livro['estoque'] == 1): ?>

                        <span class="text-warning" style="color: #ffc107; font-weight: bold;">
                            Resta 1 unidade
                        </span>

                        <?php elseif ($livro['estoque'] <= 3): ?>

                        <span class="text-warning" style="color: #ffc107; font-weight: bold;">
                            Atenção: restam apenas
                            <?= $livro['estoque'] ?> unidades
                        </span>

                        <?php else: ?>

                        <span class="text-success" style="color: #28a745;">
                            Em estoque
                        </span>

                        <?php endif; ?>

                    </div>


                    <?php if ($livro['estoque'] > 0): ?>

                    <div class="controle-quantidade">

                        <button type="button" id="btn-menos" class="btn-qtd">
                            -
                        </button>

                        <input type="number" id="qtd-livro" value="1" min="1" max="<?= $livro['estoque'] ?>" readonly
                            class="input-qtd">

                        <button type="button" id="btn-mais" class="btn-qtd">
                            +
                        </button>

                    </div>


                    <button type="button" onclick="adicionarAoCarrinho(<?= $livro['id_livro'] ?>)" class="botao">
                        Adicionar ao Carrinho
                    </button>

                    <?php else: ?>

                    <div class="livro-indisponivel-aviso">

                        <p style="
                                    color: #6c757d;
                                    font-style: italic;
                                    margin-top: 5px;
                                ">
                            Livro indisponível no momento.
                        </p>

                    </div>

                    <?php endif; ?>

                </div>


                <!-- SINOPSE -->

                <div class="sinopse-bloco">

                    <h3>Sinopse</h3>

                    <p>
                        <?php
                        echo nl2br(
                            htmlspecialchars($livro['descricao'])
                        );
                        ?>
                    </p>

                </div>


                <!-- FICHA TÉCNICA -->

                <div class="ficha-tecnica">

                    <p>
                        <b>Autor:</b>
                        <?php echo htmlspecialchars($livro['autor']); ?>
                    </p>

                    <p>
                        <b>Páginas:</b>
                        <?php echo htmlspecialchars($livro['paginas']); ?>
                        pag
                    </p>

                </div>

            </div>

        </div>

    </main>


    <script>
        function trocarImagem(imagem, elemento) {

            document.getElementById("imagemPrincipal").src = imagem;

            document
                .querySelectorAll(".livro-capa-area .thumb")
                .forEach(function (thumb) {

                    thumb.classList.remove("thumb-ativa");

                });

            elemento.classList.add("thumb-ativa");
        }
    </script>
<script src="../asset/js/bootstrap.bundle.min.js"></script>
<script src="../asset/js/script.js"></script>



</body>

</html>