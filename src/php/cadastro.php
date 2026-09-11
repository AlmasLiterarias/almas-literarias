<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário de cadastro e limpa espaços extras
    $nome               = trim($_POST['nome']);
    $cpf                = trim($_POST['cpf']);
    $email              = trim($_POST['email']);
    $nascimento         = $_POST['nascimento'];
    $pergunta_seguranca = trim($_POST['pergunta_seguranca']);
    $resposta_seguranca = trim($_POST['resposta_seguranca']);
    $senha              = $_POST['senha'];

    // Criptografa a senha do usuário com hash seguro (BCRYPT)
    $senha_segura = password_hash($senha, PASSWORD_DEFAULT);

    // Consulta de verificação para evitar e-mails duplicados no banco
    $checkSql = "SELECT id_usuario FROM usuarios WHERE email = ?";
    $stmtCheck = $conexao->prepare($checkSql);
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    
    if ($stmtCheck->get_result()->num_rows > 0) {
        die("E-mail já cadastrado. <a href='../pages/login.php'>Clique aqui para entrar</a>.");
    }
    $stmtCheck->close();

    // Insere o novo registro no MySQL incluindo pergunta e resposta de segurança
    $sql = "INSERT INTO usuarios (nome, cpf, email, nascimento, pergunta_seguranca, resposta_seguranca, senha_segura) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssssss", $nome, $cpf, $email, $nascimento, $pergunta_seguranca, $resposta_seguranca, $senha_segura);
    
    if ($stmt->execute()) {
        header("Location: ../pages/login.php?sucesso=1");
        exit();
    } else {
        echo "Erro ao cadastrar usuário: " . $conexao->error;
    }
    
    $stmt->close();
    $conexao->close();
}
?>