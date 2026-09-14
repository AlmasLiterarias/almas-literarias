<?php
include("../php/conexao.php");

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

$pastaLivros = __DIR__ . "/../asset/livro/imagens/capas-livros-pi/";
$pastas = glob($pastaLivros . "*", GLOB_ONLYDIR);

$resultado = $conexao->query("SELECT id_produto, nome_produto, pasta_imagens FROM produtos");

echo "<pre>";

while ($livro = $resultado->fetch_assoc()) {

    $nomeLivro = normalizarNome($livro['nome_produto']);
    $pastaEncontrada = null;

    foreach ($pastas as $pasta) {
        $nomePasta = normalizarNome(basename($pasta));

        if ($nomeLivro === $nomePasta) {
            $pastaEncontrada = basename($pasta);
            break;
        }

        $livroSemArtigos = preg_replace('/^(o|a|os|as)-/', '', $nomeLivro);
        $pastaSemArtigos = preg_replace('/^(o|a|os|as)-/', '', $nomePasta);

        if ($livroSemArtigos === $pastaSemArtigos) {
            $pastaEncontrada = basename($pasta);
            break;
        }
    }

    if ($pastaEncontrada) {
        $upd = $conexao->prepare("UPDATE produtos SET pasta_imagens = ? WHERE id_produto = ?");
        $upd->bind_param("si", $pastaEncontrada, $livro['id_produto']);
        $upd->execute();
        echo "OK   | {$livro['nome_produto']}  =>  {$pastaEncontrada}\n";
    } else {
        echo "FALHOU | {$livro['nome_produto']}  (nome calculado: {$nomeLivro})  -> nenhuma pasta bateu\n";
    }
}

echo "</pre>";