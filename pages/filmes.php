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
            <button class="absolute right-30 bottom-30 rounded-full w-15 h-15 bg-cinza/60 text-5xl
            cursor-pointer opacity-0 z-200 hover:bg-cinza" id="btn-desmutar" onclick="toggleMute()">
                <i class="bi bi-volume-mute"></i>
            </button>
            <video id="video-destaque" muted autoplay>
                <source src="<?= htmlspecialchars($link_filme_destaque) ?>" type="video/mp4">
                Seu navegador não suporta o elemento de vídeo.
            </video>
            <div class="container-info-destaque">
                <h2 class="titulo-destaque"><?= htmlspecialchars($titulo_destaque) ?></h2>
                <p class="descricao-destaque"><?= htmlspecialchars($descricao_destaque) ?></p>
                <p><span class="categoria-span"><?= htmlspecialchars($nome_categoria_destaque) ?></span>
                    • <?= htmlspecialchars($ano_destaque) ?></p>
                <div class="container-btn-destaque">
                    <a class="btn-assistir" href="assistir?filme=<?= htmlspecialchars($id_destaque) ?>"><i
                                class="bi bi-play-fill"></i> Assistir</a>
                    <?php
                    // verificando se o filme esta na lista ou não
                    $sql_lista = "SELECT 1 FROM minha_lista WHERE filme_id = ? AND usuario_id = ?";
                    $stmt_lista = $conexao->prepare($sql_lista);
                    $stmt_lista->bind_param("ii", $id_destaque, $_SESSION['id']);
                    $stmt_lista->execute();
                    $stmt_lista->store_result();

                    if ($stmt_lista->num_rows() > 0): ?>
                        <form action="remover_minha_lista" method="POST">
                            <!--csrf-->
                            <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                            <input type="hidden" name="destaque" id="destaque" value="1">
                            <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($id_destaque) ?>">
                            <button type="submit" class="btn-minha-lista"><i class="bi bi-check2"></i></button>
                        </form>
                    <?php else: ?>
                        <form action="adicionar_minha_lista" method="POST">
                            <!--csrf-->
                            <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                            <input type="hidden" name="destaque" id="destaque" value="1">
                            <input type="hidden" name="filme_id" id="filme_id" value="<?= htmlspecialchars($id_destaque) ?>">
                            <button type="submit" class="btn-minha-lista"><i class="bi bi-plus-lg"></i></button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <div class="sombra-destaque"></div>
        </section>
    <?php endif; ?>
    <section class="px-20 py-5 -mt-12">
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
                    <h3 class="mt-8 -mb-5 pl-2 text-3xl font-bold !z-500 text-white"><?= htmlspecialchars($row_categoria['nome']) ?></h3>
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
<?php include __DIR__ . "/../includes/final.php"; ?>
