<?php
// Conecta com o banco de dados
include('conexao.php');

// 1. Captura dos dados de texto do formulário ($_POST)
$titulo        = trim($_POST['titulo']);
$sinopse     = trim($_POST['sinopse']);
$classificacao = trim($_POST['classificacao']);
$autor       = trim($_POST['autor']);
$qtd_paginas       = trim($_POST['qtd_paginas']);
$genero        = isset($_POST['genero']) ? implode(", ", $_POST['genero']) : "";

// Validação dos campos de texto obrigatórios
if (
    empty($titulo) || empty($sinopse) || empty($classificacao) ||
    empty($autor) || empty($qtd_paginas) || empty($genero)
) {
    die("Preencha todos os campos do formulário.");
}

// ------------------------------------------------------------------
// 2. PROCESSAMENTO E SEGURANÇA DO ARQUIVO DE capa ($_LIVRO)
// ------------------------------------------------------------------

// Verificação 1: Checar se o arquivo foi enviado sem erros
if (!isset($_LIVRO['capa']) || $_LIVRO['capa']['error'] !== UPLOAD_ERR_OK) {
    echo "Erro no envio da capa do filme.";
    exit();
}

$arquivo = $_LIVRO['capa'];

// Verificação 2: Checar o tamanho do arquivo (limite de 5 MB, por exemplo)
$tamanhoMaximo = 5 * 1024 * 1024; // 5MB em bytes
if ($arquivo['size'] > $tamanhoMaximo) {
    die("O arquivo é muito pesado! O tamanho máximo permitido é 5 MB.");
}

// Verificação 3 e 4: Checar extensão permitida (jpg, jpeg, png, webp)
$extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
$extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

if (!in_array($extensao, $extensoesPermitidas)) {
    die("Formato não permitido. Envie uma capa JPG, JPEG, PNG ou WEBP.");
}

// Gerar nome único para o arquivo para evitar duplicações/sobrescritas
$novoNome = uniqid("livro_") . "." . $extensao;

// Caminho físico onde o PHP vai salvar a capa no servidor (pasta uploads/)
$diretorioDestino = "../../uploads/";

// Cria a pasta uploads caso ela ainda não exista
if (!is_dir($diretorioDestino)) {
    mkdir($diretorioDestino, 0755, true);
}

$caminhoFisico = $diretorioDestino . $novoNome;

// Caminho relativo que será gravado no MySQL (ex: "uploads/livro_65a3b.jpg")
$caminhoBanco = "uploads/" . $novoNome;

// Mover o arquivo da pasta temporária para a pasta uploads/ do projeto
if (!move_uploaded_file($arquivo['tmp_name'], $caminhoFisico)) {
    die("Falha ao salvar a capa na pasta do sistema.");
}

// ------------------------------------------------------------------
// 3. GRAVAÇÃO NO BANCO DE DADOS (MySQL)
// ------------------------------------------------------------------

$sql = "INSERT INTO filmes (titulo, capa, sinopse, genero, classificacao_indicativa, autor, qtd_paginas) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);
if (!$stmt) {
    die("Erro ao preparar a consulta: " . $conexao->error);
}

// Associa os parâmetros (8 strings = "ssssssss")
$stmt->bind_param(
    "ssssssss",
    $titulo,
    $caminhoBanco, // Salva "uploads/nome_da_capa.jpg" no banco
    $sinopse,
    $genero,
    $classificacao,
    $autor,
    $qtd_paginas
);

if (!$stmt->execute()) {
    die("Erro ao cadastrar o livro: " . $stmt->error);
}

$stmt->close();
$conexao->close();

// Redireciona para o catálogo de livros
header("Location: ../pages/catalogo.php");
exit();
?>