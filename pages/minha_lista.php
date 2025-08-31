<?php
$titulo = "Minha Lista";
include __DIR__ . "/../includes/inicio.php";

// buscando a lista do usuário
$sql_lista = "SELECT filme_id FROM minha_lista WHERE usuario_id = ?";
$stmt_lista = $conexao->prepare($sql_lista);
$stmt_lista->bind_param("i", $_SESSION['id']);
$stmt_lista->execute();
$resultado_lista = $stmt_lista->get_result();
$stmt_lista->close();
?>
<main>
    <div class="interface">
        <div class="titulo">
            <div class="txt-titulo">
                <h2><i class="bi bi-bookmark-star"></i> Minha Lista</h2>
                <p><?= $resultado_lista->num_rows ?> filmes salvos</p>
            </div>
        </div>
        <?php
        // caso não tenha filmes salvos
        if ($resultado_lista->num_rows <= 0): ?>
            <h3>Sem filmes salvos!</h3>
        <?php else: ?>
            <div class="flex flex-col gap-5">
                <?php
                // puxando os filmes da lista
                while ($row_lista = $resultado_lista->fetch_assoc()):
                    $sql_filme = "SELECT id, imagem_url, titulo, descricao, categoria_id, ano FROM filmes WHERE id = ?";
                    $stmt_filme = $conexao->prepare($sql_filme);
                    $stmt_filme->bind_param("i", $row_lista['filme_id']);
                    $stmt_filme->execute();
                    $resultado_filme = $stmt_filme->get_result();
                    $stmt_filme->close();
                    $row_filme = $resultado_filme->fetch_assoc();

                    // puxando o nome da categoria
                    $sql_categoria = "SELECT nome FROM categorias WHERE id = ?";
                    $stmt_categoria = $conexao->prepare($sql_categoria);
                    $stmt_categoria->bind_param("i", $row_filme['categoria_id']);
                    $stmt_categoria->execute();
                    $stmt_categoria->bind_result($nome_categoria);
                    $stmt_categoria->fetch();
                    $stmt_categoria->close();
                    ?>
                    <article
                            class="flex justify-between items-center gap-3 rounded-lg p-5 bg-cinza-claro border border-borda hover:bg-borda">
                        <div class="flex gap-3 items-center justify-center">
                            <img class="w-30 rounded-lg border border-borda"
                                 src="<?= htmlspecialchars($row_filme['imagem_url']) ?>"
                                 alt="Capa do filme <?= htmlspecialchars($row_filme['titulo']) ?>">
                            <div class="flex flex-col gap-3">
                                <h3 class="text-2xl font-bold"><?= htmlspecialchars($row_filme['titulo']) ?></h3>
                                <div class="flex gap-1 text-branco-texto-opaco">
                                    <p class="bg-azul/20 text-azul font-bold px-2 rounded-lg"><?= htmlspecialchars($nome_categoria) ?></p>
                                    •
                                    <p><?= htmlspecialchars($row_filme['ano']) ?></p>
                                </div>
                                <p class="text-branco-texto-opaco"><?= htmlspecialchars($row_filme['descricao']) ?></p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a class="flex items-center justify-center px-10 rounded-lg text-xl bg-white text-black hover:bg-white/80"
                               href="assistir?filme=<?= htmlspecialchars($row_lista['filme_id']) ?>"><i
                                        class="bi bi-play-fill"></i> Assistir</a>
                            <form action="remover_minha_lista" method="POST">
                                <!--csrf-->
                                <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                                <input type="hidden" name="id" id="id"
                                       value="<?= htmlspecialchars($row_filme['id']) ?>">
                                <input type="hidden" name="minha_lista" id="minha_lista" value="1">
                                <button type="submit"
                                        class="flex items-center justify-center border-3 border-borda w-10 h-10 rounded-full bg-cinza-claro/70 text-2xl cursor-pointer hover:border-white hover:bg-cinza-claro/40">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php include __DIR__ . "/../includes/final.php"; ?>
