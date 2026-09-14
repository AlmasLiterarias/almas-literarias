<?php
// Exibe erros do PHP durante o desenvolvimento (desative em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conecta com o banco de dados
include('conexao.php');

function normalizarNome($texto) {
    $texto = strtolower($texto);
    $acentos = array(
        'á'=>'a','à'=>'a','ã'=>'a','â'=>'a','ä'=>'a',
        'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
        'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',
        'ó'=>'o','ò'=>'o','õ'=>'o','ô'=>'o','ö'=>'o',
        'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c'
    );
    $texto = strtr($texto, $acentos);
    $texto = preg_replace('/\([^)]*\)/', '', $texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    return trim($texto, '-');
}

// 1. Captura dos dados de texto do formulário ( _POST)
$titulo        = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$sinopse       = isset($_POST['sinopse']) ? trim($_POST['sinopse']) : '';
$classificacao = isset($_POST['classificacao']) ? trim($_POST['classificacao']) : '';
$autor         = isset($_POST['autor']) ? trim($_POST['autor']) : '';
$qtd_paginas   = isset($_POST['qtd_paginas']) ? trim($_POST['qtd_paginas']) : '';


$preco = isset($_POST['preco']) ? (float) $_POST['preco'] : 0;

$categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';

$lancamento = isset($_POST['lancamento']) ? 1 : 0;
$destaque = isset($_POST['destaque']) ? 1 : 0;
$romanceleve = isset($_POST['romanceleve']) ? 1 : 0;
// Validação dos campos de texto obrigatórios
if (
   empty($titulo) || empty($sinopse) || empty($classificacao) ||
empty($autor) || empty($qtd_paginas) || empty($categoria)
) {
    die("Preencha todos os campos do formulário.");
}

// ------------------------------------------------------------------
// 2. PROCESSAMENTO E SEGURANÇA DO ARQUIVO DE CAPA ($_FILES)
// ------------------------------------------------------------------

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

// AJUSTE: Uso de caminho absoluto dinâmico via __DIR__ para garantir a criação do diretório
$diretorioDestino = __DIR__ . "/../../uploads/";

if (!is_dir($diretorioDestino)) {
    // Tenta criar a pasta recursivamente (0755 garante permissão de leitura/execução e escrita do proprietário)
    if (!mkdir($diretorioDestino, 0755, true)) {
        die("Falha ao criar o diretório de uploads no servidor. Verifique as permissões de escrita das pastas pai.");
    }
}

$caminhoFisico = $diretorioDestino . $novoNome;
$caminhoBanco  = "uploads/" . $novoNome;

// Mover o arquivo para a pasta de destino
if (!move_uploaded_file($arquivo['tmp_name'], $caminhoFisico)) {
    die("Falha ao salvar a capa na pasta do sistema.");
}

// ------------------------------------------------------------------
// 2.5. CRIAR PASTA DO CARROSSEL E SALVAR AS IMAGENS NELA (SEM DUPLICATAS)
// ------------------------------------------------------------------

$pastaImagens = normalizarNome($titulo);

$diretorioCarrossel = __DIR__ . "/../asset/livro/imagens/capas-livros-pi/" . $pastaImagens . "/";

if (!is_dir($diretorioCarrossel)) {
    if (!mkdir($diretorioCarrossel, 0755, true)) {
        die("Falha ao criar a pasta de imagens do livro.");
    }
}

// Guarda os hashes (impressões digitais) das imagens já salvas nesta pasta,
// pra nunca salvar o mesmo conteúdo duas vezes — incluindo o que já
// existia de tentativas anteriores
$hashesSalvos = [];

if (is_dir($diretorioCarrossel)) {
    $arquivosExistentes = scandir($diretorioCarrossel);
    foreach ($arquivosExistentes as $arquivoExistente) {
        if ($arquivoExistente === "." || $arquivoExistente === "..") continue;
        $hashesSalvos[] = md5_file($diretorioCarrossel . $arquivoExistente);
    }
}

// Copia a capa principal pra dentro da pasta do carrossel (só se ainda não existir)
$hashCapa = md5_file($caminhoFisico);
if (!in_array($hashCapa, $hashesSalvos)) {
    copy($caminhoFisico, $diretorioCarrossel . $novoNome);
    $hashesSalvos[] = $hashCapa;
}

// Salva as fotos extras enviadas (se houver), pulando qualquer uma
// que seja idêntica a uma imagem já salva (incluindo a própria capa)
if (isset($_FILES['imagens_carrossel']) && is_array($_FILES['imagens_carrossel']['tmp_name'])) {

    foreach ($_FILES['imagens_carrossel']['tmp_name'] as $indice => $tmpNome) {

        if ($_FILES['imagens_carrossel']['error'][$indice] !== UPLOAD_ERR_OK) {
            continue;
        }

        $nomeOriginal = $_FILES['imagens_carrossel']['name'][$indice];
        $extensaoExtra = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

        if (!in_array($extensaoExtra, $extensoesPermitidas)) {
            continue;
        }

        $hashArquivo = md5_file($tmpNome);

        // Se o conteúdo já foi salvo antes (é a mesma foto), pula
        if (in_array($hashArquivo, $hashesSalvos)) {
            continue;
        }

        $novoNomeExtra = uniqid("livro_") . "." . $extensaoExtra;
        move_uploaded_file($tmpNome, $diretorioCarrossel . $novoNomeExtra);
        $hashesSalvos[] = $hashArquivo;
    }
}

// ------------------------------------------------------------------
// 3. GRAVAÇÃO NO BANCO DE DADOS (Tabela: produtos)
// ------------------------------------------------------------------

$editora = "Editora Padrão";
$idioma = "Português";
$tipo = "Capa Comum";
$quantidade = 10;

$sql = "INSERT INTO produtos (
    nome_produto,
    img_produto,
    descricao_produto,
    categoria_produto,
    classificacao_indicativa_produto,
    autor_produto,
    paginas_produto,
    editora_produto,
    preco_produto,
    idioma_produto,
    lancamento_produto,
    tipo_produto,
    quantidade_produto,
    exibir_lancamento,
    destaque_produto,
romance_leve,
pasta_imagens
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);
if (!$stmt) {
    die("Erro ao preparar a consulta: " . $conexao->error);
}

$stmt->bind_param(
    "ssssssisdssiiiis",
    $titulo,
    $caminhoBanco,
    $sinopse,
    $categoria,
    $classificacao,
    $autor,
    $qtd_paginas,
    $editora,
    $preco,
    $idioma,
    $tipo,
    $quantidade,
    $lancamento,
    $destaque,
    $romanceleve,
    $pastaImagens   
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