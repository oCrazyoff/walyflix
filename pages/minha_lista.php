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
                    <h2><i class="bi bi-bookmark"></i> Minha Lista</h2>
                    <p><span id="num-filmes"><?= htmlspecialchars($resultado_lista->num_rows) ?></span> filmes salvos
                    </p>
                </div>
            </div>
            <?php
            // caso não tenha filmes salvos
            if ($resultado_lista->num_rows <= 0): ?>
                <div class="flex flex-col gap-5 items-center justify-center mt-10">
                    <i class="bi bi-bookmark text-6xl text-branco-texto-opaco bg-cinza-claro p-5 rounded-full"></i>
                    <h3 class="text-2xl font-bold">A sua lista está vazia</h3>
                    <p class="text-xl text-branco-texto-opaco text-center w-full lg:w-1/2">
                        Adicione filmes à sua lista para assistir depois. Você pode fazer isso a partir da página de
                        filmes.
                    </p>
                    <a href="filmes" class="px-5 py-3 text-xl rounded-lg bg-azul hover:bg-azul-hover">Explorar
                        Filmes</a>
                </div>
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
                        <a href="assistir?filme=<?= htmlspecialchars($row_lista['filme_id']) ?>"
                           class="flex justify-between items-center gap-3 rounded-lg p-3 lg:p-5 bg-cinza-claro border
                           border-borda hover:bg-borda">
                            <div class="flex gap-3 items-center justify-center">
                                <img class="w-15 lg:w-30 rounded-lg border border-borda"
                                     src="<?= htmlspecialchars($row_filme['imagem_url']) ?>"
                                     alt="Capa do filme <?= htmlspecialchars($row_filme['titulo']) ?>">
                                <div class="flex flex-col gap-3">
                                    <h3 class="text-lg lg:text-2xl font-bold"><?= htmlspecialchars($row_filme['titulo']) ?></h3>
                                    <div class="flex gap-1 text-branco-texto-opaco text-sm lg:text-md">
                                        <p class="bg-azul/20 text-azul font-bold px-2 rounded-lg"><?= htmlspecialchars($nome_categoria) ?></p>
                                        •
                                        <p><?= htmlspecialchars($row_filme['ano']) ?></p>
                                    </div>
                                    <p class="text-branco-texto-opaco hidden lg:block"><?= htmlspecialchars($row_filme['descricao']) ?></p>
                                </div>
                            </div>
                            <form class="form-minha-lista" action="toggle_minha_lista" method="POST">
                                <!--csrf-->
                                <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                                <input type="hidden" name="filme_id" id="filme_id"
                                       value="<?= htmlspecialchars($row_filme['id']) ?>">
                                <button type="submit"
                                        class="text-2xl lg:text-3xl p-2 rounded-md cursor-pointer hover:bg-cinza">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <script src="<?= BASE_URL . "assets/js/minha_lista.js" ?>"></script>
<?php include __DIR__ . "/../includes/final.php"; ?>