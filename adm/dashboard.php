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
    </div>
</main>
<?php include __DIR__ . "/../includes/final.php"; ?>
