<?php
require __DIR__ . '/../valida.php';
header('Content-Type: application/json; charset=utf-8');

try {
    // Valida e sanitiza a query de busca
    $q = isset($_GET['q']) ? trim(strip_tags($_GET['q'])) : '';


    if (!$q || mb_strlen($q) < 0) {
        echo json_encode([]);
        exit;
    }

    // consultando com LIKE
    $busca = "%" . $q . "%";

    $sql = "SELECT id, imagem_url, titulo
        FROM filmes
        WHERE titulo LIKE ?
        LIMIT 10";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $busca);
    $stmt->execute();
    $resultado = $stmt->get_result();


    $filmes = [];
    while ($row = $resultado->fetch_assoc()) {
        // Sanitiza cada valor antes de enviar
        $filmes[] = array_map(function ($valor) {
            return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
        }, $row);
    }

    echo json_encode($filmes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Exception $e) {
    // Loga o erro internamente sem expor detalhes
    registrarErro($_SESSION['id'] ?? 0, "Erro ao buscar filmes: " . $q, $e);
    echo json_encode(['erro' => 'Ocorreu um erro interno.']);
    exit;
}
