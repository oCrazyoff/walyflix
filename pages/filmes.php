<?php
$titulo = "Filmes";
include __DIR__ . "/../includes/inicio.php";

// buscando o filme de destaque
$sql = "SELECT id, titulo, descricao, categoria_id, ano, link_filme FROM filmes WHERE destaque = 1 LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
if ($resultado->num_rows > 0) {
    while ($row_destaque = $resultado->fetch_assoc()) {
        $id_destaque = $row_destaque['id'];
        $titulo_destaque = $row_destaque['titulo'];
        $descricao_destaque = $row_destaque['descricao'];
        $link_filme_destaque = $row_destaque['link_filme'];
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
            <video id="video-destaque" autoplay>
                <source src="<?= htmlspecialchars($link_filme_destaque) ?>" type="video/mp4">
                Seu navegador não suporta o elemento de vídeo.
            </video>
            <div class="container-info-destaque">
                <h2 class="titulo-destaque"><?= htmlspecialchars($titulo_destaque) ?></h2>
                <p class="descricao-destaque"><?= htmlspecialchars($descricao_destaque) ?></p>
                <div class="container-btn-destaque">
                    <a class="btn-assistir" href="assistir?filme=<?= htmlspecialchars($id_destaque) ?>"><i class="bi bi-play-fill"></i> Assistir</a>
                    <a class="btn-mais-info" href="info?filme=<?= htmlspecialchars($id_destaque) ?>"><i
                                class="bi bi-info-circle"></i> Mais Informações</a>
                </div>
            </div>
            <div class="sombra-destaque"></div>
        </section>
    <?php endif; ?>
    <section class="px-20 py-5">
        <?php
        // puxando todas categorias
        $sql = "SELECT id, nome FROM categorias";
        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();

        if ($resultado->num_rows > 0) :
            while ($row_categoria = $resultado->fetch_assoc()) :

                // puxando todos filmes da categoria
                $sql = "SELECT id, titulo, imagem_url FROM filmes WHERE categoria_id = ?";
                $stmt_filmes = $conexao->prepare($sql);
                $stmt_filmes->bind_param("i", $row_categoria['id']);
                $stmt_filmes->execute();
                $resultado_filmes = $stmt_filmes->get_result();
                $stmt_filmes->close();

                if ($resultado_filmes->num_rows > 0) : ?>
                    <h3 class="mt-8 -mb-5 pl-2 text-3xl font-bold"><?= htmlspecialchars($row_categoria['nome']) ?></h3>
                    <div class="swiper w-full">
                        <div class="swiper-wrapper">
                            <?php while ($row_filmes = $resultado_filmes->fetch_assoc()) : ?>
                                <div class="swiper-slide !w-max">
                                    <a class="p-1" href="info?filme=<?= htmlspecialchars($row_filmes['id']) ?>">
                                        <?php
                                        // verificando se tem capa ou não
                                        if (!empty($row_filmes['imagem_url'])): ?>
                                            <img class="ml-1 p-1 w-[20rem] h-[30rem] object-cover rounded-lg hover:ring-2"
                                                 src="<?= htmlspecialchars($row_filmes['imagem_url']) ?>"
                                                 alt="Capa do filme <?= htmlspecialchars($row_filmes['titulo']) ?>">
                                        <?php else: ?>
                                            <img class="ml-1 p-1 w-[20rem] h-[30rem] object-cover rounded-lg hover:ring-2"
                                                 src="https://www.protrusmoto.com/wp-content/uploads/revslider/home5/placeholder-1200x500.png"
                                                 alt="Filme sem capa">
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <!-- Botão anterior -->
                        <div class="swiper-button-prev !text-white !left-0 !top-1/2 !h-[90%] !w-20 flex items-center
                        justify-start bg-gradient-to-r from-black/60 to-transparent cursor-pointer z-10 !m-0
                        !-translate-y-1/2">
                        </div>

                        <!-- Botão próximo -->
                        <div class="swiper-button-next !text-white !right-0 !top-1/2 !h-[90%] !w-20 flex items-center
                        justify-end bg-gradient-to-l from-black to-transparent cursor-pointer z-10 !m-0
                        !-translate-y-1/2">
                        </div>
                    </div>

                <?php endif; ?>
            <?php endwhile;
        else:
            $_SESSION['resposta'] = "Nenhuma categoria ou filme encontrado!";
        endif;
        ?>
    </section>
</main>
<script>
    // swiper
    const swiper = new Swiper('.swiper', {
        // Melhora a UX ao mostrar um cursor de "agarrar"
        grabCursor: true,

        // botões de navegação
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // espaço entre slides
        spaceBetween: 20,

        // responsivo
        slidesPerView: "auto",
        freeMode: true
    });

    // colocando filme em destaque para começar na metade
    const video = document.getElementById("video-destaque");

    video.addEventListener("loadedmetadata", () => {
        video.currentTime = video.duration / 2;

        // abaixando o volume
        video.volume = 0.5;
    });
</script>
<?php include __DIR__ . "/../includes/final.php"; ?>
