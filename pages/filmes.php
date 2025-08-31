<?php
$titulo = "Filmes";
include __DIR__ . "/../includes/inicio.php";

// buscando o filme de destaque
$sql = "SELECT titulo, descricao, categoria_id, ano, link_filme FROM filmes WHERE destaque = 1 LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
if ($resultado->num_rows > 0) {
    while ($row_destaque = $resultado->fetch_assoc()) {
        $titulo_destaque = $row_destaque['titulo'];
        $descricao_destaque = $row_destaque['descricao'];
        $link_filme_destaque = $row_destaque['link_filme'];
    }
} else {
    $titulo_destaque = "Sem filmes!";
    $descricao_destaque = "...";
    $link_filme_destaque = "https://images.wallpaperscraft.com/image/single/error_wallpaper_image_151413_2560x1440.jpg";
}
?>
<main>
    <section class="w-full h-[calc(100dvh-5rem)] overflow-hidden">
        <video id="video-destaque" class="object-cover h-[calc(100dvh-5rem)] w-full opacity-80" autoplay>
            <source src="<?= htmlspecialchars($link_filme_destaque) ?>" type="video/mp4">
            Seu navegador não suporta o elemento de vídeo.
        </video>
        <div class="flex flex-col gap-5 absolute top-3/6 left-50 w-1/3 z-100">
            <h2 class="text-6xl font-bold text-shadow-lg/30"><?= htmlspecialchars($titulo_destaque) ?></h2>
            <p class="text-branco-texto-opaco text-2xl text-shadow-lg/30"><?= htmlspecialchars($descricao_destaque) ?></p>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1/2 shadow-[inset_0_-150px_100px_-30px_rgba(0,0,0,1),_inset_0_-80px_50px_-20px_rgba(0,0,0,0.9)]"></div>
    </section>
</main>
<script>
    const video = document.getElementById("video-destaque");

    video.addEventListener("loadedmetadata", () => {
        video.currentTime = video.duration / 2;
    });
</script>
<?php include __DIR__ . "/../includes/final.php"; ?>
