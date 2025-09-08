<?php
require __DIR__ . '/../valida.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $usuario_id = $_SESSION['id'] ?? null;
    $filme_id = filter_input(INPUT_POST, 'filme_id', FILTER_VALIDATE_INT);

    if (!$usuario_id || !$filme_id) {
        throw new Exception("Dados inválidos");
    }

    // Verifica se já existe
    $sql = "SELECT 1 FROM minha_lista WHERE usuario_id = ? AND filme_id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $usuario_id, $filme_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res->num_rows > 0;
    $stmt->close();

    if ($exists) {
        // Remover da lista
        $sql = "DELETE FROM minha_lista WHERE usuario_id = ? AND filme_id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $usuario_id, $filme_id);
        $stmt->execute();
        $stmt->close();

        // Pegar a nova contagem de filmes do usuário
        $sql = "SELECT COUNT(*) AS total FROM minha_lista WHERE usuario_id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        echo json_encode([
            'sucesso' => true,
            'acao' => 'removido',
            'nova_contagem' => (int)$total
        ], JSON_UNESCAPED_UNICODE);

    } else {
        // Adicionar na lista
        $sql = "INSERT INTO minha_lista (usuario_id, filme_id) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $usuario_id, $filme_id);
        $stmt->execute();
        $stmt->close();

        echo json_encode([
            'sucesso' => true,
            'acao' => 'adicionado'
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (Exception $e) {
    registrarErro($_SESSION['id'] ?? 0, "Erro toggle_minha_lista", $e);
    echo json_encode(['sucesso' => false, 'erro' => 'Ocorreu um erro interno.']);
    exit;
}