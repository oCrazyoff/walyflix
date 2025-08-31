<?php
require __DIR__ . '/../valida.php';
header('Content-Type: application/json');

// Pega o ID via GET e valida
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo json_encode(['erro' => 'ID inválido']);
    exit;
}

try {
    // Consulta segura usando prepared statement
    $stmt = $conexao->prepare("
        SELECT nome
        FROM categorias
        WHERE id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        echo json_encode(['erro' => 'Categoria não encontrada']);
        exit;
    }

    $row = $resultado->fetch_assoc();

    // Retorna o JSON com os dados
    echo json_encode($row);

    $stmt->close();
} catch (Exception $e) {
    // Log de erro
    registrarErro($_SESSION['id'], "Erro ao buscar filme com ID $id", $e);
    echo json_encode(['erro' => 'Erro ao buscar filme']);
    exit;
}
