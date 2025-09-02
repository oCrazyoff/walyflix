<?php
require __DIR__ . '/../valida.php';
header('Content-Type: application/json; charset=utf-8');

try {
    // Valida e sanitiza a query de busca
    $q = trim(strip_tags($_GET['q']));

    if (!$q || mb_strlen($q) < 3) {
        echo json_encode([]);
        exit;
    }

    // Consulta segura usando LIKE com prepared statement
    $sql = "SELECT id, imagem_url FROM filmes WHERE titulo LIKE CONCAT('%', ?, '%') LIMIT 20";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $q);
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
