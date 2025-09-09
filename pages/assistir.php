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

if (!$stmt->fetch()) {
    $_SESSION['resposta'] = "Filme não encontrado!";
    header("Location: " . BASE_URL . "filmes");
    exit();
}

$stmt->close();

$titulo = $titulo_filme;
include __DIR__ . "/../includes/inicio.php";
?>

    <main class="fixed inset-0 bg-black">

        <a class="fixed top-[1rem] lg:top-[0.5rem] left-[90%] lg:left-[2rem] text-3xl z-50 rotate-90 lg:rotate-0"
           href="filmes">
            <i class="bi bi-arrow-left"></i>
        </a>

        <div class="player-container-custom fixed inset-0 lg:flex lg:items-center lg:justify-center">
            <video id="player" playsinline controls>
                <source src="<?= htmlspecialchars($link_filme) ?>" type="video/mp4">
                Seu navegador não suporta o elemento de vídeo.
            </video>
        </div>
    </main>

    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Inicializa o player de forma simples
            const player = new Plyr('#player');

            // Tenta dar play automaticamente quando o player estiver pronto
            player.on('ready', (event) => {
                player.play().catch(() => {
                    console.log("Autoplay bloqueado pelo navegador.");
                });
            });
        });
    </script>

<?php include __DIR__ . "/../includes/final.php"; ?>