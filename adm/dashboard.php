<?php
$titulo = "Dashboard";
include __DIR__ . "/../includes/inicio.php";

// funções dos cards
function usuariosTotais()
{
    global $conexao;

    $sql = "SELECT COUNT(*) as total FROM usuarios";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $total = $resultado->fetch_assoc();
    $stmt->close();
    return $total['total'];
}

function filmesTotais()
{
    global $conexao;

    $sql = "SELECT COUNT(*) as total FROM filmes";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $total = $resultado->fetch_assoc();
    $stmt->close();
    return $total['total'];
}

function filmeDestaque()
{
    global $conexao;

    $sql = "SELECT titulo FROM filmes WHERE destaque = 1";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $total = $resultado->fetch_assoc();
    $stmt->close();
    return $total['titulo'];
}

function filmeMaisSalvo()
{
    global $conexao;

    $sql = "SELECT f.id, f.titulo, COUNT(*) AS total_salvos
            FROM minha_lista ml
            JOIN filmes f ON f.id = ml.filme_id
            GROUP BY f.id, f.titulo
            ORDER BY total_salvos DESC
            LIMIT 1;
            ";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $total = $resultado->fetch_assoc();
    $stmt->close();
    return $total['titulo'];
}

// gráficos
function filmesPorCategoria()
{
    global $conexao;
    $sql = "SELECT c.nome, COUNT(f.id) as total 
            FROM categorias c
            LEFT JOIN filmes f ON f.categoria_id = c.id
            GROUP BY c.nome";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $categorias = [];
    $quantidades = [];
    while ($row = $resultado->fetch_assoc()) {
        $categorias[] = $row['nome'];
        $quantidades[] = $row['total'];
    }
    $stmt->close();
    return ['categorias' => $categorias, 'quantidades' => $quantidades];
}

$dadosCategorias = filmesPorCategoria();

function topFilmesMaisSalvos()
{
    global $conexao;
    $sql = "SELECT f.titulo, COUNT(*) as total_salvos
            FROM minha_lista ml
            JOIN filmes f ON f.id = ml.filme_id
            GROUP BY f.id, f.titulo
            ORDER BY total_salvos DESC
            LIMIT 5";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $titulos = [];
    $salvos = [];
    while ($row = $resultado->fetch_assoc()) {
        $titulos[] = $row['titulo'];
        $salvos[] = $row['total_salvos'];
    }
    $stmt->close();
    return ['titulos' => $titulos, 'salvos' => $salvos];
}

$dadosTopFilmes = topFilmesMaisSalvos();
?>
<main>
    <div class="interface">
        <div class="titulo">
            <div class="txt-titulo">
                <h2><i class="bi bi-columns-gap"></i> Dashboard</h2>
                <p>Bem-vindo de volta, <?= htmlspecialchars($_SESSION['nome']) ?>. Aqui está um resumo da sua
                    plataforma.</p>
            </div>
        </div>
        <div class="container-cards">
            <div class="card">
                <h3>Usuários Totais <i class="bi bi-people"></i></h3>
                <span><?= usuariosTotais() ?></span>
            </div>
            <div class="card">
                <h3>Filmes Cadastrados <i class="bi bi-film"></i></h3>
                <span><?= filmesTotais() ?></span>
            </div>
            <div class="card">
                <h3>Filme em Destaque <i class="bi bi-star"></i></h3>
                <span><?= filmeDestaque() ?></span>
            </div>
            <div class="card">
                <h3>Filme Mais Salvo <i class="bi bi-bookmark"></i></h3>
                <span><?= filmeMaisSalvo() ?></span>
            </div>
        </div>
        <div class="container-graficos">
            <div class="grafico">
                <h3><i class="bi bi-film"></i> Filmes por Categoria</h3>
                <p>Quantidade de filmes por gênero</p>
                <canvas id="graficoCategorias"></canvas>
            </div>
            <div class="grafico">
                <h3><i class="bi bi-bookmark"></i> Top 5 Filmes Mais Salvos</h3>
                <p>Filmes com mais aparições na "Minha Lista"</p>
                <canvas id="graficoTopFilmes"></canvas>
            </div>
        </div>
    </div>
</main>
<script>
    const ctxCategorias = document.getElementById('graficoCategorias').getContext('2d');
    new Chart(ctxCategorias, {
        type: 'bar', // Mudamos de 'pie' para 'bar' conforme o pedido de arredondamento de barras
        data: {
            labels: <?= json_encode($dadosCategorias['categorias']) ?>,
            datasets: [{
                label: 'Quantidade de Filmes',
                data: <?= json_encode($dadosCategorias['quantidades']) ?>,
                backgroundColor: [
                    'rgba(0, 129, 204, 0.6)',   // Azul Oceano
                    'rgba(255, 107, 107, 0.6)', // Vermelho Melancia
                    'rgba(14, 186, 139, 0.6)',  // Verde Esmeralda
                    'rgba(255, 183, 3, 0.6)',   // Amarelo Sol
                    'rgba(141, 71, 192, 0.6)',  // Roxo Ametista
                    'rgba(255, 123, 44, 0.6)'   // Laranja Vibrante
                ],
                borderColor: [
                    'rgba(0, 129, 204, 1)',
                    'rgba(255, 107, 107, 1)',
                    'rgba(14, 186, 139, 1)',
                    'rgba(255, 183, 3, 1)',
                    'rgba(141, 71, 192, 1)',
                    'rgba(255, 123, 44, 1)'
                ],
                borderWidth: 2, // Largura da borda
                borderRadius: 8 // Arredondamento das bordas das barras
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    const ctxTopFilmes = document.getElementById('graficoTopFilmes').getContext('2d');
    new Chart(ctxTopFilmes, {
        type: 'bar',
        data: {
            labels: <?= json_encode($dadosTopFilmes['titulos']) ?>,
            datasets: [{
                label: 'Salvos',
                data: <?= json_encode($dadosTopFilmes['salvos']) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)', // Azul com 60% de opacidade
                borderColor: 'rgba(54, 162, 235, 1)',    // Borda azul sólida
                borderWidth: 2, // Largura da borda
                borderRadius: 8 // Arredondamento das bordas das barras
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
<?php include __DIR__ . "/../includes/final.php"; ?>
