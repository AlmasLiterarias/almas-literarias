<?php
// Conecta com o banco de dados
include('conexao.php');

// 1. Captura dos dados de texto do formulário ($_POST)
$titulo        = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$sinopse       = isset($_POST['sinopse']) ? trim($_POST['sinopse']) : '';
$classificacao = isset($_POST['classificacao']) ? trim($_POST['classificacao']) : '';
$autor         = isset($_POST['autor']) ? trim($_POST['autor']) : '';
$qtd_paginas   = isset($_POST['qtd_paginas']) ? trim($_POST['qtd_paginas']) : '';
$genero        = isset($_POST['genero']) ? implode(", ", $_POST['genero']) : "";

// Validação dos campos de texto obrigatórios
if (
    empty($titulo) || empty($sinopse) || empty($classificacao) ||
    empty($autor) || empty($qtd_paginas) || empty($genero)
) {
    die("Preencha todos os campos do formulário.");
}

// ------------------------------------------------------------------
// 2. PROCESSAMENTO E SEGURANÇA DO ARQUIVO DE CAPA ($_FILES)
// ------------------------------------------------------------------

// CORREÇÃO 1 e 2: Uso do $_FILES e alinhamento com o name="imagem" do HTML
if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
    die("Erro no envio da capa do livro. Certifique-se de selecionar um arquivo válido.");
}

$arquivo = $_FILES['imagem'];

// Verificação do tamanho do arquivo (limite de 5 MB)
$tamanhoMaximo = 5 * 1024 * 1024; 
if ($arquivo['size'] > $tamanhoMaximo) {
    die("O arquivo é muito pesado! O tamanho máximo permitido é 5 MB.");
}

// Verificação de extensão permitida
$extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
$extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

if (!in_array($extensao, $extensoesPermitidas)) {
    die("Formato não permitido. Envie uma capa JPG, JPEG, PNG ou WEBP.");
}

// Gerar nome único para o arquivo
$novoNome = uniqid("livro_") . "." . $extensao;

// Caminho físico onde o PHP vai salvar a capa no servidor
$diretorioDestino = "../../uploads/";

if (!is_dir($diretorioDestino)) {
    mkdir($diretorioDestino, 0755, true);
}

$caminhoFisico = $diretorioDestino . $novoNome;
$caminhoBanco = "uploads/" . $novoNome;

// Mover o arquivo para a pasta de destino
if (!move_uploaded_file($arquivo['tmp_name'], $caminhoFisico)) {
    die("Falha ao salvar a capa na pasta do sistema.");
}

// ------------------------------------------------------------------
// 3. GRAVAÇÃO NO BANCO DE DADOS (Tabela: produtos)
// ------------------------------------------------------------------

// CORREÇÃO 3: Ajuste da consulta SQL para a tabela 'produtos' atualizada
$sql = "INSERT INTO produtos (
            nome_produto, 
            img_produto, 
            descricao_produto, 
            categoria_produto, 
            classificacao_indicativa_produto, 
            autor_produto, 
            paginas_produto,
            editora_produto,
            preco,
            idioma_produto,
            lancamento_produto,
            tipo_produto,
            quantidade_produto
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'Editora Padrão', 0, 'Português', NOW(), 'Capa Comum', 10)";

$stmt = $conexao->prepare($sql);
if (!$stmt) {
    die("Erro ao preparar a consulta: " . $conexao->error);
}

// Bind dos 7 parâmetros dinâmicos enviados pelo formulário
// "ssssssi" = 6 Strings e 1 Inteiro (qtd_paginas)
$stmt->bind_param(
    "ssssssi",
    $titulo,
    $caminhoBanco,
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

// Redireciona para o catálogo de livros/produtos
header("Location: ../pages/catalogo.php");
exit();
?>