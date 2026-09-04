<?php
    include('../php/conexao.php');
    session_start();
    
$id_usuario = $_SESSION['id_usuario'];

/* Endereço para envio */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'novo_endereco') {
    $apelido = trim($_POST['apelido_local']);
    $rua = trim($_POST['rua']);
    $numero = trim($_POST['numero']);
    $bairro = trim($_POST['bairro']);
    $cidade = trim($_POST['cidade']);
    $estado = trim($_POST['estado']);
    $cep = trim($_POST['cep']);

    $sql_insert = "INSERT INTO enderecos (id_usuario, apelido_local, rua, numero, bairro, cidade, estado, cep) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conexao->prepare($sql_insert);
    $stmt_insert->bind_param("isssssss", $id_usuario, $apelido, $rua, $numero, $bairro, $cidade, $estado, $cep);
    $stmt_insert->execute();
    $stmt_insert->close();
    
    header("Location: painel.php?sucesso=endereco");
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

$sql = "SELECT nome, nome_social, pronomes, email, nascimento, tipo_usuario, acessibilidade_alto_contraste, pergunta_seguranca FROM usuarios WHERE id_usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();


$eh_moderador = in_array($usuario['tipo_usuario'], ['moderador', 'admin']);

$denuncias = [];
if ($eh_moderador) {
    $sql_denuncias = "SELECT c.id_comentario, c.comentario, c.data_comentario, u.nome AS autor, l.titulo AS livro_titulo, l.id_livro
                      FROM comentarios c
                      INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                      INNER JOIN livros l ON c.id_livro = l.id_livro
                      WHERE c.status = 'analise'
                      ORDER BY c.data_comentario DESC";
    $res_denuncias = $conexao->query($sql_denuncias);
    if ($res_denuncias) {
        while ($row = $res_denuncias->fetch_assoc()) {
            $denuncias[] = $row;
        }
    }
}

$tipos_rotulo = [
    'admin'     => 'Administrador',
    'moderador' => 'Moderador',      
    'comum'     => 'Usuário Comum'    
];

$rotulo_atual = $tipos_rotulo[$usuario['tipo_usuario']] ?? 'Usuário';

$sql_favoritos = "SELECT l.id_livro, l.titulo, l.imagem, l.genero, fav.data_favoritado 
                  FROM favoritos fav 
                  INNER JOIN livros f ON fav.id_livro = l.id_livro 
                  WHERE fav.id_usuario = ? 
                  ORDER BY fav.data_favoritado DESC";
$stmt_fav_list = $conexao->prepare($sql_favoritos);
$stmt_fav_list->bind_param("i", $id_usuario);
$stmt_fav_list->execute();
$res_favoritos = $stmt_fav_list->get_result();

$favoritos = [];
while ($row = $res_favoritos->fetch_assoc()) {
    $favoritos[] = $row;
}
$stmt_fav_list->close();

$sql_compras = "SELECT p.id_pedido, p.valor_total, p.status, p.data_pedido, 
                       GROUP_CONCAT(CONCAT(l.titulo, ' (x', ip.quantidade, ')') SEPARATOR ', ') AS livros_comprados
                FROM pedidos p
                INNER JOIN itens_pedido ip ON p.id_pedido = ip.id_pedido
                INNER JOIN livros f ON ip.id_livro = l.id_livro
                WHERE p.id_usuario = ?
                GROUP BY p.id_pedido
                ORDER BY p.data_pedido DESC";
$stmt_compras = $conexao->prepare($sql_compras);
$stmt_compras->bind_param("i", $id_usuario);
$stmt_compras->execute();
$res_compras = $stmt_compras->get_result();


?>
/* Gerenciar endereço */
<div id="aba-enderecos" class="aba-conteudo">
                <h1>Meus Endereços</h1>
                <p class="descricao">Gerencie seus endereços para agilizar o checkout.</p>
                
                <?php if (!empty($enderecos)): ?>
                    <div class="grid-enderecos-painel">
                        <?php foreach ($enderecos as $end): ?>
                            <div class="card-endereco-painel">
                                <strong class="endereco-apelido"><?= htmlspecialchars($end['apelido_local'] ?? 'Endereço') ?></strong>
                                <p class="endereco-detalhe">
                                    <?= htmlspecialchars($end['rua']) ?>, <?= htmlspecialchars($end['numero']) ?><br>
                                    Bairro: <?= htmlspecialchars($end['bairro']) ?><br>
                                    <?= htmlspecialchars($end['cidade']) ?> / <?= htmlspecialchars($end['estado']) ?><br>
                                    CEP: <?= htmlspecialchars($end['cep']) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="descricao">Você ainda não possui nenhum endereço cadastrado.</p>
                <?php endif; ?>

                <!-- Cadastra novo endereço -->
                <h3>Cadastrar Novo Endereço</h3>
                <form action="painel.php" method="POST" class="form-endereco-painel">
                    <input type="hidden" name="acao" value="novo_endereco">
                    
                    <div class="campo">
                        <label>Apelido do Local (ex: Casa, Trabalho):</label>
                        <input type="text" name="apelido_local" required placeholder="Minha Casa">
                    </div>
                    <div class="form-linha-dupla">
                        <div class="campo campo-flex-1">
                            <label>CEP:</label>
                            <input type="text" name="cep" required placeholder="00000-000">
                        </div>
                        <div class="campo campo-flex-2">
                            <label>Rua:</label>
                            <input type="text" name="rua" required>
                        </div>
                    </div>
                    <div class="form-linha-dupla">
                        <div class="campo campo-flex-1">
                            <label>Número:</label>
                            <input type="text" name="numero" required>
                        </div>
                        <div class="campo campo-flex-2">
                            <label>Bairro:</label>
                            <input type="text" name="bairro" required>
                        </div>
                    </div>
                    <div class="form-linha-dupla">
                        <div class="campo campo-flex-2">
                            <label>Cidade:</label>
                            <input type="text" name="cidade" required>
                        </div>
                        <div class="campo campo-flex-1">
                            <label>Estado (UF):</label>
                            <input type="text" name="estado" maxlength="2" required placeholder="SP">
                        </div>
                    </div>

                    <button type="submit" class="btn-salvar">Salvar Endereço</button>
                </form>
            </div>
                
            <!-- /* HTML da segurança*/ -->
            <!-- /*Segurança*/ -->
            <form action="../php/dashboard.php" method="post" id="aba-seguranca" class="aba-conteudo perfil-sessao">
                <h1>Dados de Login e Segurança</h1>
                <p class="descricao">Atualize sua senha, e-mail ou pergunta de segurança.</p>

            <!-- /*Atualizar e-mail*/ -->
                <div class="campo">
                    <label for="email">E-mail:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                </div>    

           <!-- /*Alterar senha*/' -->
                <div class="campo">
                    <label for="senha_nova">Nova Senha (deixe em branco se não quiser alterar):</label>
                    <input type="password" name="senha_nova" id="senha_nova" placeholder="Nova senha">
                </div>
            
            <!-- /*Confirmar a senha nova*/ -->
                <div class="campo">
                    <label for="confirmar_senha">Confirme a Nova Senha:</label>
                    <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirme a nova senha">
                </div> 

                <hr class="divisor-seguranca">

                <h3>Pergunta e Resposta de Segurança</h3>
                <p class="descricao">Utilizada caso você precise recuperar sua senha na tela de login.</p>

                <div class="campo">
                    <label for="nova_pergunta">Pergunta de Segurança:</label>
                    <select name="nova_pergunta" id="nova_pergunta">
                        <option value="">Selecione uma pergunta...</option>
                        <option value="Qual é o nome do seu primeiro pet?" <?= ($usuario['pergunta_seguranca'] === 'Qual é o nome do seu primeiro pet?') ? 'selected' : '' ?>>Qual é o nome do seu primeiro pet?</option>
                        <option value="Qual é o nome da sua cidade natal?" <?= ($usuario['pergunta_seguranca'] === 'Qual é o nome da sua cidade natal?') ? 'selected' : '' ?>>Qual é o nome da sua cidade natal?</option>
                        <option value="Qual o nome do seu livro favorito?" <?= ($usuario['pergunta_seguranca'] === 'Qual o nome do seu livro favorito?') ? 'selected' : '' ?>>Qual o nome do seu livro favorito?</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="nova_resposta">Nova Resposta Secreta:</label>
                    <input type="text" name="nova_resposta" id="nova_resposta" placeholder="Digite a resposta secreta (deixe em branco para manter a atual)">
                </div>

                <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): ?>            
                <div class="campo"> /*"===" significa estritamente igual*/
                    <label for="tipo_usuario">Tipo de Conta:</label>
                    <input type="text" id="tipo_usuario" value="<?php echo htmlspecialchars($rotulo_atual); ?>" disabled>
                </div>
                <?php endif; ?>

                
                <div class="campo campo-seguranca">
                    <label for="senha_atual">Senha Atual (Necessária para salvar alterações):</label>
                    <input type="password" name="senha_atual" id="senha_atual" placeholder="Sua senha atual" required>
                </div>

                <div class="botoes-form">
                    <button type="submit" class="btn-salvar">Salvar Alterações</button>
                </div>
            </form>

                        <form action="../php/dashboard.php" method="post" id="aba-preferencias" class="aba-conteudo perfil-sessao">
                <h1>Preferências & Acessibilidade</h1>
                <p class="descricao">Ajuste a visualização e configurações da sua conta.</p>

                <div class="campo campo-checkbox">
                    <label class="label-checkbox-acessibilidade">
                        <input type="checkbox" name="alto_contraste" value="1" <?php echo ($usuario['acessibilidade_alto_contraste'] == 1) ? 'checked' : ''; ?>>
                        Ativar Modo de Alto Contraste (Acessibilidade)
                    </label>
                </div>

                <div class="botoes-form">
                    <button type="submit" class="btn-salvar">Salvar Alterações</button>
                </div>

                <div class="danger-zone">
                    <h3>Excluir Conta</h3>
                    <p class="descricao">Esta ação é irreversível e excluirá permanentemente seu perfil.</p>
                    <button type="button" id="btnAbrirModalExcluir" class="botao-perigo">Excluir Minha Conta</button>
                </div>
            </form>

            /*Aba dos favoritos*/
            <div id="aba-favoritos" class="aba-conteudo">
                <h1>Meus Favoritos</h1>
                <p class="descricao">Livros que você adicionou à sua lista pessoal.</p>

                <?php if (!empty($favoritos)): ?>
                    <div class="grid-favoritos">
                        <?php foreach ($favoritos as $fav): ?>
                            <div class="card-favorito">
                                <?php if (!empty($fav['imagem'])): ?>
                                    <img src="../../<?= htmlspecialchars($fav['imagem']) ?>" alt="<?= htmlspecialchars($fav['titulo']) ?>">
                                <?php else: ?>
                                    <div class="capa-placeholder">Sem Imagem</div>
                                <?php endif; ?>
                                
                                <h3><?= htmlspecialchars($fav['titulo']) ?></h3>
                                <p><?= htmlspecialchars($fav['genero']) ?></p>
                                <small>Favoritado em: <?= date('d/m/Y', strtotime($fav['data_favoritado'])) ?></small>
                                
                                <a href="livro.php?id=<?= $fav['id_livro'] ?>" class="btn-salvar link btn-ver-livro-favorito">Ver livro</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="descricao">Você ainda não favoritou nenhum livro.</p>
                <?php endif; ?>
            </div>

            /**Histórico de compras/
            <div id="aba-compras" class="aba-conteudo">
                <h1>Histórico de Compras</h1>
                <p class="descricao">Acompanhe todos os seus pedidos de livros realizados.</p>

                <?php if ($res_compras && $res_compras->num_rows > 0): ?>
                    <table class="tabela-compras">
                        <thead>
                            <tr>
                                <th>#Pedido</th>
                                <th>Data</th>
                                <th>Itens</th>
                                <th>Total</th>
                                <th>Status / Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($ped = $res_compras->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?= $ped['id_pedido'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($ped['data_pedido'])) ?></td>
                                    <td><?= htmlspecialchars($ped['livros_comprados']) ?></td>
                                    <td>R$ <?= number_format($ped['valor_total'], 2, ',', '.') ?></td>
                                    <td>
                                        <span class="status-tag <?= $ped['status'] ?>"><?= ucfirst($ped['status']) ?></span>
                                        
                                        <?php if ($ped['status'] === 'pendente'): ?>
                                            <form action="../php/cancelar_pedido.php" method="POST" style="display:inline-block; margin-top: 5px;" onsubmit="return confirm('Deseja realmente cancelar este pedido?');">
                                                <input type="hidden" name="id_pedido" value="<?= $ped['id_pedido'] ?>">
                                                <button type="submit" class="botao-perigo" style="padding: 4px 8px; font-size: 12px;">Cancelar</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="descricao">Você ainda não realizou nenhuma compra.</p>
                <?php endif; ?>
            </div>

                    <!-- ABA GERENCIAR PEDIDOS -->
            <div id="aba-gerenciar-pedidos" class="aba-conteudo">
                <h1>Gerenciamento de Pedidos</h1>
                <p class="descricao">Acompanhe e atualize o status dos pedidos realizados pelos clientes.</p>

                <?php if ($res_todos_pedidos && $res_todos_pedidos->num_rows > 0): ?>
                    <table class="tabela-compras">
                        <thead>
                            <tr>
                                <th>#Pedido</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Itens</th>
                                <th>Total</th>
                                <th>Status / Alterar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($ped_adm = $res_todos_pedidos->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?= $ped_adm['id_pedido'] ?></td>
                                    <td><?= htmlspecialchars($ped_adm['cliente_nome']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($ped_adm['data_pedido'])) ?></td>
                                    <td><?= htmlspecialchars($ped_adm['livros_comprados']) ?></td>
                                    <td>R$<?= number_format($ped_adm['valor_total'], 2, ',', '.') ?></td>
                                    <td>
                                        <form action="../php/atualizar_status_pedido.php" method="POST" class="form-status-inline">
                                            <input type="hidden" name="id_pedido" value="<?= $ped_adm['id_pedido'] ?>">
                                            <select name="status" class="select-status-admin">
                                                <option value="pendente" <?= $ped_adm['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                                <option value="pago" <?= $ped_adm['status'] === 'pago' ? 'selected' : '' ?>>Pago</option>
                                                <option value="cancelado" <?= $ped_adm['status'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                            </select>
                                            <button type="submit" class="btn-salvar btn-status-admin"><span class="icone">
                                                <img src="../assets/img/icons/checked.png" alt="Salvar"></span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="descricao">Nenhum pedido registrado no sistema.</p>
                <?php endif; ?>
            </div>
