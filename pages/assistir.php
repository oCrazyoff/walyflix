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
<main class="p-0">
    <a class="fixed top-[0.5rem] left-[2rem] text-3xl z-100" href="filmes"><i class="bi bi-arrow-left"></i></a>
    <video class="relative min-w-full h-dvh" autoplay controls>
        <source src="<?= htmlspecialchars($link_filme) ?>" type="video/mp4">
        Seu navegador não suporta o elemento de vídeo.
    </video>
</main>
<?php include __DIR__ . "/../includes/final.php"; ?>
