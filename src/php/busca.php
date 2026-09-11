<?php
include('conexao.php');

$pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : "";
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : "";

$sql = "SELECT 
            id_livro,
            titulo, 
            descricao AS sinopse, 
            imagem AS capa, 
            genero AS categoria, 
            classificacao_indicativa 
        FROM livros 
        WHERE titulo LIKE ?";

if (!empty($categoria)) {
    $sql .= " AND genero LIKE ?";
}

$stmt = $conexao->prepare($sql);
$paramPesquisa = "%" . $pesquisa . "%";

if (!empty($categoria)) {
    $paramCategoria = "%" . $categoria . "%";
    $stmt->bind_param("ss", $paramPesquisa, $paramCategoria);
} else {
    $stmt->bind_param("s", $paramPesquisa);
}

$stmt->execute();
$resultado = $stmt->get_result();

// Monta a lista de livros para a interface
$livros = [];
while ($livro = $resultado->fetch_assoc()) {
    $livros[] = $livro;
}
?>