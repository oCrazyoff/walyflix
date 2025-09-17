<?php
$titulo = "Filmes";
include __DIR__ . "/../includes/inicio.php";

// buscando o filme de destaque
$sql = "SELECT id, titulo, descricao, categoria_id, ano, imagem_deitada_url, link_filme FROM filmes WHERE destaque = 1 LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
if ($resultado->num_rows > 0) {
    while ($row_destaque = $resultado->fetch_assoc()) {
        $id_destaque = $row_destaque['id'];
        $titulo_destaque = $row_destaque['titulo'];
        $descricao_destaque = $row_destaque['descricao'];
        $categoria_id_destaque = $row_destaque['categoria_id'];
        $ano_destaque = $row_destaque['ano'];
        $capa_deitada = $row_destaque['imagem_deitada_url'];
        $link_filme_destaque = $row_destaque['link_filme'];

        // puxando o nome da categoria
        $sql = "SELECT nome FROM categorias WHERE id = ?";
        $stmt_categoria = $conexao->prepare($sql);
        $stmt_categoria->bind_param("i", $categoria_id_destaque);
        $stmt_categoria->execute();
        $stmt_categoria->bind_result($nome_categoria_destaque);
        $stmt_categoria->fetch();
        $stmt_categoria->close();
    }
} else {
    $sem_destaque = true;
}
?>
<main>
    <?php
    // caso não tenha filme destaque não mostrar a section
    if (!isset($sem_destaque) || $sem_destaque != true) : ?>
        <section class="section-destaque">
            <?php
            // verificando se tem capa cadastrada
            if (!empty($capa_deitada)):?>
                <img class="capa-info absolute" src="<?= htmlspecialchars($capa_deitada) ?>"
                     alt="Capa do filme <?= htmlspecialchars($titulo_destaque) ?>">
            <?php else: ?>
                <img class="capa-info absolute"
                     src="https://www.protrusmoto.com/wp-content/uploads/revslider/home5/placeholder-1200x500.png"
                     alt="Filme sem capa">
            <?php endif; ?>
            <button class="absolute right-[5%] lg:right-30 bottom-[80%] lg:bottom-30 rounded-full w-15 h-15 bg-cinza/60 text-5xl
            cursor-pointer opacity-0 z-200 hover:bg-cinza" id="btn-desmutar" onclick="toggleMute()">
                <i class="bi bi-volume-mute"></i>
            </button>
            <video id="video-destaque" muted autoplay>
                <source src="<?= htmlspecialchars($link_filme_destaque) ?>" type="video/mp4">
                Seu navegador não suporta o elemento de vídeo.
            </video>
            <div class="container-info-destaque">
                <h2 class="titulo-destaque"><?= htmlspecialchars($titulo_destaque) ?></h2>
                <p>
                    <?= htmlspecialchars($nome_categoria_destaque) ?>
                    <span class="span-categoria-ano">•</span>
                    <?= htmlspecialchars($ano_destaque) ?>
                </p>
                <div class="container-btn-destaque flex-row">
                    <a class="btn-assistir w-[80%] lg:w-auto"
                       href="assistir?filme=<?= htmlspecialchars($id_destaque) ?>"><i
                                class="bi bi-play-fill"></i> Assistir</a>
                    <?php
                    // verificando se o filme esta na lista ou não
                    $sql_lista = "SELECT 1 FROM minha_lista WHERE filme_id = ? AND usuario_id = ?";
                    $stmt_lista = $conexao->prepare($sql_lista);
                    $stmt_lista->bind_param("ii", $id_destaque, $_SESSION['id']);
                    $stmt_lista->execute();
                    $resultado_lista = $stmt_lista->get_result();
                    $stmt_lista->close();
                    ?>
                    <form class="form-minha-lista" action="toggle_minha_lista" method="POST">
                        <!--csrf-->
                        <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                        <input type="hidden" name="filme_id" id="filme_id"
                               value="<?= htmlspecialchars($id_destaque) ?>">
                        <button type="submit"
                                class="btn-minha-lista">
                            <?php if ($resultado_lista->num_rows > 0): ?>
                                <i class="bi bi-check2"></i>
                            <?php else: ?>
                                <i class="bi bi-plus-lg"></i>
                            <?php endif; ?>
                        </button>
                    </form>
                </div>
            </div>
            <div class="sombra-destaque"></div>
        </section>
    <?php endif; ?>
    <section class="px-5 lg:px-20 py-5 -mt-8 lg:-mt-12">
        <?php
        // puxando todas categorias
        $sql = "SELECT id, nome FROM categorias";
        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();

        if ($resultado->num_rows > 0) :
            $top10 = 0;
            while ($row_categoria = $resultado->fetch_assoc()) :
                $top10++;

                // puxando todos filmes da categoria
                $sql = "SELECT id, titulo, imagem_url FROM filmes WHERE categoria_id = ?";
                $stmt_filmes = $conexao->prepare($sql);
                $stmt_filmes->bind_param("i", $row_categoria['id']);
                $stmt_filmes->execute();
                $resultado_filmes = $stmt_filmes->get_result();
                $stmt_filmes->close();

                if ($resultado_filmes->num_rows > 0) : ?>
                    <h3 class="mt-5 lg:mt-8 pl-2 text-2xl lg:text-3xl font-bold text-white"><?= htmlspecialchars($row_categoria['nome']) ?></h3>
                    <div class="filmes-container group">
                        <button class="scroll-btn scroll-left hidden lg:block"><i class="bi bi-chevron-left"></i>
                        </button>
                        <div class="container-filmes">
                            <?php while ($row_filmes = $resultado_filmes->fetch_assoc()) : ?>
                                <a class="p-1" href="info?filme=<?= htmlspecialchars($row_filmes['id']) ?>">
                                    <?php if (!empty($row_filmes['imagem_url'])): ?>
                                        <img class="capa-filme"
                                             src="<?= htmlspecialchars($row_filmes['imagem_url']) ?>"
                                             alt="Capa do filme <?= htmlspecialchars($row_filmes['titulo']) ?>">
                                    <?php else: ?>
                                        <img class="capa-filme"
                                             src="https://www.protrusmoto.com/wp-content/uploads/revslider/home5/placeholder-1200x500.png"
                                             alt="Filme sem capa">
                                    <?php endif; ?>
                                </a>
                            <?php endwhile; ?>
                        </div>
                        <button class="scroll-btn scroll-right hidden lg:block"><i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                    <?php
                    // mostrando os top 10 filmes mais salvos
                    if ($top10 == 2) : ?>
                        <h3 class="mt-5 lg:mt-8 pl-2 text-2xl lg:text-3xl font-bold text-white">
                            Top 10 filmes
                        </h3>
                        <div class="filmes-container group">
                            <button class="scroll-btn scroll-left hidden lg:block"><i class="bi bi-chevron-left"></i>
                            </button>
                            <div class="container-filmes">
                                <?php
                                $sql = "SELECT f.id, f.titulo, f.imagem_url, COUNT(ml.id) AS total_salvos
                                        FROM filmes f
                                        LEFT JOIN minha_lista ml ON f.id = ml.filme_id
                                        GROUP BY f.id, f.titulo, f.imagem_url
                                        ORDER BY total_salvos DESC, f.titulo ASC LIMIT 10;";
                                $stmt = $conexao->prepare($sql);
                                $stmt->execute();
                                $resultado_top = $stmt->get_result();
                                $stmt->close();

                                // controle dos numeros das posicoes
                                $posicao_top_10 = 1;

                                while ($row_top = $resultado_top->fetch_assoc()) : ?>
                                    <div class="container-top-10">
                                        <span class="posicao-top-10 text-outline"><?= $posicao_top_10 ?></span>
                                        <a class="p-1" href="info?filme=<?= htmlspecialchars($row_top['id']) ?>">
                                            <?php if (!empty($row_top['imagem_url'])): ?>
                                                <img class="capa-filme"
                                                     src="<?= htmlspecialchars($row_top['imagem_url']) ?>"
                                                     alt="Capa do filme <?= htmlspecialchars($row_top['titulo']) ?>">
                                            <?php else: ?>
                                                <img class="capa-filme"
                                                     src="https://www.protrusmoto.com/wp-content/uploads/revslider/home5/placeholder-1200x500.png"
                                                     alt="Filme sem capa">
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <?php $posicao_top_10++; endwhile; ?>
                            </div>
                            <button class="scroll-btn scroll-right hidden lg:block"><i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endwhile;
        else:
            $_SESSION['resposta'] = "Nenhuma categoria ou filme encontrado!";
        endif;
        ?>
    </section>
</main>
<script>
    // botão de desmutar filme destaque
    const btn_desmutar = document.getElementById("btn-desmutar");
    const icone_btn_desmutar = btn_desmutar.querySelector("i");

    function toggleMute() {
        // Inverte o mute
        video.muted = !video.muted;

        // Troca o ícone conforme o estado
        if (video.muted) {
            icone_btn_desmutar.className = "bi bi-volume-mute";
        } else {
            icone_btn_desmutar.className = "bi bi-volume-down";
        }
    }

    // sumindo com a capa após o carregamento da pagina e mostrando o btn de desmutar
    window.onload = function () {
        document.querySelector(".capa-info").style.opacity = "0";
        btn_desmutar.style.opacity = "1";
    };

    // colocando filme em destaque para começar na metade
    const video = document.getElementById("video-destaque");

    video.addEventListener("loadedmetadata", () => {
        video.currentTime = video.duration / 2;
    });
</script>
<script src="<?= BASE_URL . "assets/js/btn_carrossel.js" ?>"></script>
<script src="<?= BASE_URL . "assets/js/minha_lista.js" ?>"></script>
<?php include __DIR__ . "/../includes/final.php"; ?>
