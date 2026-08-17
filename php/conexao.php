<?php 
    #variaveis de acesso (servidor, usuario, senha, banco, conexao)
$servidor = "localhost"; #Endereco do banco (PC) 
$usuario = "root"; #Quem tem permissao 
$senha = "senac"; #Chave de acesso
$banco = "almas_literarias"; #Nome do banco de dados que vamos usar
    #OBS: a sting entre aspas e oque voce deve ver
$conexao = new mysqli($servidor, $usuario, $senha, $banco, 3307); #mysqli e a ferramenta que o php usa para conversar com o MySQL. O "i" vem de "melhorado"

if($conexao->connect_error){ #Se a conexao der erro
    die("Erro: " . $conexao->connect_error); #Mate a conexao
}
?>