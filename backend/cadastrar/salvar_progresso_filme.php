<?php
require __DIR__ . '/../valida.php';
header('Content-Type: application/json');

try {
    $usuario_id = $_SESSION['id'] ?? null;
    $filme_id = filter_input(INPUT_POST, 'filme_id', FILTER_VALIDATE_INT);
    $segundos = filter_input(INPUT_POST, 'segundos', FILTER_VALIDATE_INT);

    if (!$usuario_id || !$filme_id || $segundos === false) {
        throw new Exception("Dados inválidos");
    }

    // Verifica se já existe progresso
    $sql = "SELECT id FROM filmes_progresso WHERE usuario_id = ? AND filme_id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $usuario_id, $filme_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $exists = $res->num_rows > 0;
    $stmt->close();

    if ($exists) {
        // Atualiza os segundos
        $sql = "UPDATE filmes_progresso 
                   SET segundos = ?, atualizado_em = NOW() 
                 WHERE usuario_id = ? AND filme_id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iii", $segundos, $usuario_id, $filme_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insere novo progresso
        $sql = "INSERT INTO filmes_progresso (usuario_id, filme_id, segundos) 
                VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iii", $usuario_id, $filme_id, $segundos);
        $stmt->execute();
        $stmt->close();
    }

} catch (Exception $e) {
    registrarErro($_SESSION['id'] ?? 0, "Erro salvar_progresso", $e);
    echo json_encode(['sucesso' => false, 'erro' => 'Ocorreu um erro interno.']);
    exit;
}