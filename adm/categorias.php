<?php
$titulo = "Categorias";
include __DIR__ . "/../includes/inicio.php";

// puxando todos os filmes
$sql = "SELECT id, titulo, descricao, categoria_id, ano, imagem_url, destaque FROM filmes";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
?>
    <main>
        <div class="interface">
            <div class="titulo">
                <div class="txt-titulo">
                    <h2><i class="bi bi-tags"></i> Categorias</h2>
                    <p>Gerencie as categorias de filmes do WalyFlix</p>
                </div>
                <button onclick="abrirCadastrarModal('categorias')"><i class="bi bi-plus"></i>
                    <span>Nova Categoria</span></button>
            </div>
            <div class="container-categorias">
                <?php
                // puxando todas categorias
                $sql = "SELECT id, nome FROM categorias";
                $stmt = $conexao->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $stmt->close();

                if ($resultado->num_rows > 0) :
                    while ($row = $resultado->fetch_assoc()) :
                        ?>
                        <article class="item-categoria">
                            <div class="txt">
                                <h3><?= htmlspecialchars($row['nome']) ?></h3>
                                <div class="container-acoes">
                                    <button onclick="abrirEditarModal('categorias', <?= htmlspecialchars($row['id']) ?>)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form class="deletar"
                                          action="deletar_categorias?id=<?= htmlspecialchars($row['id']) ?>"
                                          method="POST">
                                        <input type="hidden" name="csrf" id="csrf"
                                               value="<?= htmlspecialchars(gerarCSRF()) ?>">
                                        <button type="submit"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </div>
                            <p class="mt-3">
                                <?php
                                // puxando quantos filmes essa categoria possui
                                $sql = "SELECT COUNT(*) FROM filmes WHERE categoria_id = ?";
                                $stmt = $conexao->prepare($sql);
                                $stmt->bind_param("i", $row['id']);
                                $stmt->execute();
                                $stmt->bind_result($total_filmes);
                                $stmt->fetch();
                                $stmt->close();

                                if ($total_filmes > 0) :
                                    echo htmlspecialchars($total_filmes) . " filmes cadastrados";
                                else :
                                    echo "Sem filmes cadastrados";
                                endif;
                                ?>
                            </p>
                        </article>
                    <?php endwhile; ?>
                <?php else: $_SESSION['resposta'] = "Nenhuma categoria encontrada!" ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
<?php
$tipoModal = 'categorias';
include __DIR__ . "/../includes/modal.php";
?>
<?php include __DIR__ . "/../includes/final.php"; ?>