<?php
// pegando o ID do filme a ser informado
$id = filter_input(INPUT_GET, 'filme', FILTER_VALIDATE_INT);

session_start();
include __DIR__ . "/../backend/conexao.php";

// puxando informações do filme
$sql = "SELECT titulo, descricao, categoria_id, ano, imagem_deitada_url FROM filmes WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($titulo_filme, $descricao, $categoria_id, $ano, $capa_deitada);

// verificando se não veio alguma informação do banco
if (!$stmt->fetch()) {
    $_SESSION['resposta'] = "Filme não encontrado!";
    header("Location: " . BASE_URL . "filmes");
    exit();
}

$stmt->close();

// puxando o nome da categoria
$sql_categoria = "SELECT nome FROM categorias WHERE id = ?";
$stmt_categoria = $conexao->prepare($sql_categoria);
$stmt_categoria->bind_param("i", $categoria_id);
$stmt_categoria->execute();
$stmt_categoria->bind_result($nome_categoria);
$stmt_categoria->fetch();
$stmt_categoria->close();

$titulo = $titulo_filme;
include __DIR__ . "/../includes/inicio.php";
?>
<main class="p-0">
    <section class="section-destaque h-[calc(100dvh-5rem)]">
        <?php
        // verificando se tem capa cadastrada
        if (!empty($capa_deitada)):?>
            <img class="capa-info" src="<?= htmlspecialchars($capa_deitada) ?>"
                 alt="Capa do filme <?= htmlspecialchars($titulo_filme) ?>">
        <?php else: ?>
            <img class="capa-info"
                 src="https://www.protrusmoto.com/wp-content/uploads/revslider/home5/placeholder-1200x500.png"
                 alt="Filme sem capa">
        <?php endif; ?>
        <div class="container-info-destaque">
            <h2 class="titulo-destaque"><?= htmlspecialchars($titulo_filme) ?></h2>
            <p class="descricao-destaque"><?= htmlspecialchars($descricao) ?></p>
            <p><span class="categoria-span"><?= htmlspecialchars($nome_categoria) ?></span>
                • <?= htmlspecialchars($ano) ?></p>
            <div class="container-btn-destaque">
                <a class="btn-assistir" href="assistir?filme=<?= htmlspecialchars($id) ?>"><i
                            class="bi bi-play-fill"></i> Assistir</a>
                <?php
                // verificando se o filme esta na lista ou não
                $sql_lista = "SELECT 1 FROM minha_lista WHERE filme_id = ? AND usuario_id = ?";
                $stmt_lista = $conexao->prepare($sql_lista);
                $stmt_lista->bind_param("ii", $id, $_SESSION['id']);
                $stmt_lista->execute();
                $stmt_lista->store_result();

                if ($stmt_lista->num_rows() > 0): ?>
                    <form action="remover_minha_lista" method="POST">
                        <!--csrf-->
                        <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                        <input type="hidden" name="info" id="info" value="1">
                        <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($id) ?>">
                        <button type="submit" class="btn-minha-lista"><i class="bi bi-check2"></i></button>
                    </form>
                <?php else: ?>
                    <form action="adicionar_minha_lista" method="POST">
                        <!--csrf-->
                        <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                        <input type="hidden" name="info" id="info" value="1">
                        <input type="hidden" name="filme_id" id="filme_id" value="<?= htmlspecialchars($id) ?>">
                        <button type="submit" class="btn-minha-lista"><i class="bi bi-plus-lg"></i></button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <div class="sombra-preto-destaque"></div>
    </section>
</main>
<?php include __DIR__ . "/../includes/final.php"; ?>
