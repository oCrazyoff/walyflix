<?php
$titulo = "Gerenciar Filmes";
include __DIR__ . "/../includes/inicio.php";

// sistema de busca
$busca = $_GET['s'] ?? NULL;

if ($busca != NULL) {
    $busca_param = "%" . $busca . "%";
    $sql = "SELECT id, titulo, descricao, categoria_id, ano, imagem_url, imagem_deitada_url, destaque 
            FROM filmes 
            WHERE titulo LIKE ? 
            ORDER BY titulo ASC";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $busca_param);
} else {
    $sql = "SELECT id, titulo, descricao, categoria_id, ano, imagem_url, imagem_deitada_url, destaque 
            FROM filmes 
            ORDER BY titulo ASC";
    $stmt = $conexao->prepare($sql);
}

// puxando todos os filmes
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
$qtd_filmes = 0;
?>
<main>
    <div class="interface">
        <div class="titulo">
            <div class="txt-titulo">
                <h2><i class="bi bi-collection-play"></i> Gerenciar Filmes</h2>
                <p>Visualize, adicione e gerencie todos os filmes do catálogo.</p>
            </div>
            <button onclick="abrirCadastrarModal('filmes')"><i class="bi bi-plus"></i> <span>Novo Filme</span></button>
        </div>
        <div class="container-table-titulo">
            <h3>Catálogo de Filmes</h3>
            <p>
                <?= $resultado->num_rows ?> filmes encontrados
            </p>
            <div class="flex items-center justify-center gap-3 bg-cinza w-full mt-3 mb-4 rounded-lg overflow-hidden
            border border-borda">
                <i class="bi bi-search flex items-center justify-center pl-5 text-2xl text-azul"></i>
                <input class="p-3 w-full focus:outline-0 focus:border-0" type="search" name="busca-filme"
                       id="busca-filme"
                       placeholder="Pesquise por nome">
            </div>
            <div class="container-table">
                <?php if ($resultado->num_rows > 0): ?>
                    <table>
                        <thead>
                        <tr>
                            <th>Capa</th>
                            <th>Capa Deitada</th>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Lançamento</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while (($row = $resultado->fetch_assoc()) && $qtd_filmes != 20): ?>
                            <?php $qtd_filmes++; ?>
                            <tr>
                                <td>
                                    <?php
                                    // caso não tiver imagem cadastrada mostrar placeholder
                                    if (!empty($row['imagem_url'])): ?>
                                        <img class="capa" src="<?= htmlspecialchars($row['imagem_url']) ?>"
                                             alt="Capa do filme <?= htmlspecialchars($row['titulo']) ?>">
                                    <?php else: ?>
                                        <img class="capa"
                                             src="https://i0.wp.com/www.bishoprook.com/wp-content/uploads/2021/05/placeholder-image-gray-16x9-1.png?ssl=1"
                                             alt="Filme sem capa">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    // caso não tenha imagem deitada cadastrada mostrar placeholder
                                    if (!empty($row['imagem_deitada_url'])): ?>
                                        <img class="capa" src="<?= htmlspecialchars($row['imagem_deitada_url']) ?>"
                                             alt="Capa do filme <?= htmlspecialchars($row['titulo']) ?>">
                                    <?php else: ?>
                                        <img class="capa"
                                             src="https://i0.wp.com/www.bishoprook.com/wp-content/uploads/2021/05/placeholder-image-gray-16x9-1.png?ssl=1"
                                             alt="Filme sem capa">
                                    <?php endif; ?>

                                </td>
                                <td><?= htmlspecialchars($row['titulo']) ?></td>
                                <td class="truncate max-w-50"><?= htmlspecialchars($row['descricao']) ?></td>
                                <td>
                                    <?php
                                    // buscando o nome da categoria
                                    $stmt = $conexao->prepare("SELECT nome FROM categorias WHERE id = ?");
                                    $stmt->bind_param("i", $row['categoria_id']);
                                    $stmt->execute();
                                    $stmt->bind_result($nome_categoria);
                                    $stmt->fetch();
                                    $stmt->close();

                                    echo htmlspecialchars($nome_categoria);
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($row['ano']) ?></td>
                                <td>
                                    <?php
                                    // verificando se o filme esta em destaque ou não
                                    if ($row['destaque'] == 0) :
                                        ?>
                                        <p id="catalogo">Catálogo</p>
                                    <?php else: ?>
                                        <p id="destaque"><i class="bi bi-star-fill"></i> Em Destaque</p>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="container-acoes">
                                        <form action="filme_destaque?id=<?= htmlspecialchars($row['id']) ?>"
                                              method="POST">
                                            <!--csrf-->
                                            <input type="hidden" name="csrf" id="csrf"
                                                   value="<?= htmlspecialchars(gerarCSRF()) ?>">
                                            <button type="submit" class="text-amber-500">
                                                <?php
                                                // verificando se o filme atual é destaque ou não
                                                if ($row['destaque'] == 0) : ?>
                                                    <i class="bi bi-star"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-star-fill"></i>
                                                <?php endif; ?>
                                            </button>
                                        </form>
                                        <button onclick="abrirEditarModal('filmes', <?= htmlspecialchars($row['id']) ?>)">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form class="deletar"
                                              action="deletar_filmes?id=<?= htmlspecialchars($row['id']) ?>"
                                              method="POST">
                                            <input type="hidden" name="csrf" id="csrf"
                                                   value="<?= htmlspecialchars(gerarCSRF()) ?>">
                                            <button type="submit"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else:
                    $_SESSION['resposta'] = "Nenhum filme encontrado!" ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<script>
    // lógica de buscar filmes
    const input_busca = document.getElementById('busca-filme');

    input_busca.addEventListener('keypress', function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            const busca = this.value.trim();

            const url = new URL(window.location.href);
            if (busca) {
                url.searchParams.set('s', busca);
            } else {
                url.searchParams.delete('s');
            }

            window.location.href = url.toString();
        }
    });
</script>
<?php
$tipoModal = 'filmes';
include __DIR__ . "/../includes/modal.php";
?>
<?php include __DIR__ . "/../includes/final.php"; ?>
