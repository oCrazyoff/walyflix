<?php
// pegando o ID do filme a ser assistido
$id = filter_input(INPUT_GET, 'filme', FILTER_VALIDATE_INT);

session_start();
include __DIR__ . "/../backend/conexao.php";

// puxando informações do filme
$sql = "SELECT titulo, link_filme FROM filmes WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($titulo_filme, $link_filme);

// verificando se não veio alguma informação do banco
if (!$stmt->fetch()) {
    $_SESSION['resposta'] = "Filme não encontrado!";
    header("Location: " . BASE_URL . "filmes");
    exit();
}

$stmt->close();

$titulo = $titulo_filme;
include __DIR__ . "/../includes/inicio.php";
?>
<main class="fixed inset-0 bg-black flex items-center justify-center overflow-hidden">

    <!-- Botão Voltar -->
    <a class="fixed top-[1rem] lg:top-[0.5rem] left-[90%] lg:left-[2rem] text-3xl z-50 rotate-90 lg:rotate-0"
       href="filmes">
        <i class="bi bi-arrow-left"></i>
    </a>

    <!-- Loading -->
    <div id="loading" class="absolute inset-0 flex items-center justify-center bg-black z-40">
        <div class="w-16 h-16 border-4 border-gray-300 border-t-azul rounded-full animate-spin"></div>
    </div>

    <!-- Vídeo -->
    <video id="videoPlayer" class="assistir-filme z-30"
           autoplay
           controls>
        <source src="<?= htmlspecialchars($link_filme) ?>" type="video/mp4">
        Seu navegador não suporta o elemento de vídeo.
    </video>
</main>

<script>
    const video = document.getElementById("videoPlayer");
    const loading = document.getElementById("loading");

    // Quando o vídeo estiver carregado o suficiente para começar a tocar
    video.addEventListener("canplay", () => {
        loading.style.display = "none";
    });
</script>

<?php include __DIR__ . "/../includes/final.php"; ?>