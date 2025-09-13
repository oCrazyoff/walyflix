<?php
// pegando o ID do filme a ser informado
$filme_id = filter_input(INPUT_GET, 'filme', FILTER_VALIDATE_INT);

session_start();
include __DIR__ . "/../backend/conexao.php";

// puxando informações do filme
$sql = "SELECT titulo, descricao, categoria_id, ano, imagem_deitada_url FROM filmes WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $filme_id);
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

// puxando filmes da mesma categoria menos o proprio filme
$sql = "SELECT id, titulo, imagem_url FROM filmes WHERE categoria_id = ? AND id != ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $categoria_id, $filme_id);
$stmt->execute();
$resultado_recomendacao = $stmt->get_result();
$stmt->close();

$titulo = $titulo_filme;
include __DIR__ . "/../includes/inicio.php";
?>
<main class="p-0">
    <?php
    // trocando a altura da section caso tenha recomendações
    if ($resultado_recomendacao->num_rows > 0) : ?>
    <section class="section-destaque lg:h-[calc(90dvh-5rem)]">
        <?php else: ?>
        <section class="section-destaque h-[calc(100dvh-5rem)]">
            <?php endif; ?>
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
                <p>
                    <?= htmlspecialchars($nome_categoria) ?>
                    <span class="span-categoria-ano">•</span>
                    <?= htmlspecialchars($ano) ?>
                </p>
                <div class="container-btn-destaque">
                    <a class="btn-assistir" href="assistir?filme=<?= htmlspecialchars($filme_id) ?>"><i
                                class="bi bi-play-fill"></i> Assistir</a>
                    <?php
                    // verificando se o filme esta na lista ou não
                    $sql_lista = "SELECT 1 FROM minha_lista WHERE filme_id = ? AND usuario_id = ?";
                    $stmt_lista = $conexao->prepare($sql_lista);
                    $stmt_lista->bind_param("ii", $filme_id, $_SESSION['id']);
                    $stmt_lista->execute();
                    $resultado_lista = $stmt_lista->get_result();
                    $stmt_lista->close();
                    ?>
                    <form class="form-minha-lista-info" action="toggle_minha_lista" method="POST">
                        <!--csrf-->
                        <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                        <input type="hidden" name="filme_id" id="filme_id"
                               value="<?= htmlspecialchars($filme_id) ?>">
                        <button type="submit"
                                class="btn-minha-lista-info">
                            <?php if ($resultado_lista->num_rows > 0): ?>
                                <i class="bi bi-check2"></i>
                            <?php else: ?>
                                <i class="bi bi-plus-lg"></i>
                            <?php endif; ?>
                            <p>Minha Lista</p>
                        </button>
                    </form>
                </div>
            </div>
            <div class="sombra-preto-destaque"></div>
        </section>
        <?php if ($resultado_recomendacao->num_rows > 0) : ?>
            <section class="bg-black lg:px-5 lg:px-20 pt-0 pb-15 lg:pb-0">
                <h2 class="w-full border-b font-bold text-2xl lg:text-3xl pb-2">Você também pode gostar</h2>
                <div class="max-w-full overflow-auto">
                    <div class="flex py-5 gap-1 lg:gap-3">
                        <?php while ($row_filmes = $resultado_recomendacao->fetch_assoc()) : ?>
                            <a class="p-1" href="info?filme=<?= htmlspecialchars($row_filmes['id']) ?>">
                                <?php
                                // verificando se tem capa ou não
                                if (!empty($row_filmes['imagem_url'])): ?>
                                    <img class="capa-filme"
                                         src="<?= htmlspecialchars($row_filmes['imagem_url']) ?>"
                                         alt="Capa do filme <?= htmlspecialchars($row_filmes['titulo']) ?>">
                                <?php else: ?>
                                    <img class="ml-1 p-1 w-[9rem] h-[13rem] lg:w-[20rem] lg:h-[30rem] object-cover rounded-lg hover:ring-2"
                                         src="https://www.protrusmoto.com/wp-content/uploads/revslider/home5/placeholder-1200x500.png"
                                         alt="Filme sem capa">
                                <?php endif; ?>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
            <script src="<?= BASE_URL . "assets/js/swiper.js" ?>"></script>
        <?php endif; ?>
</main>
<script src="<?= BASE_URL . "assets/js/minha_lista.js" ?>"></script>
<?php include __DIR__ . "/../includes/final.php"; ?>
