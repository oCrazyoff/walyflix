<?php
require __DIR__ . '/../valida.php';
header("Content-type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Número inteiro
    $filme_id = filter_input(INPUT_POST, 'filme_id', FILTER_VALIDATE_INT);
    $usuario_id = $_SESSION['id'];

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: " . BASE_URL . "filmes");
        exit;
    }

    try {
        // verificando se existe na lista
        $sql = "SELECT id FROM minha_lista WHERE filme_id = ? AND usuario_id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $filme_id, $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $resultado = $resultado->fetch_assoc();
        $stmt->close();

        if ($resultado) {
            // existe então - remover
            $deletar = $conexao->prepare("DELETE FROM minha_lista WHERE id = ?");
            $deletar->bind_param("i", $resultado['id']);
            $deletar->execute();
            $deletar->close();
            echo json_encode(['sucesso' => true, 'acao' => 'removido']);
        } else {
            // não existe então - adicionar
            $inserir = $conexao->prepare("INSERT INTO minha_lista (filme_id, usuario_id) VALUES (?, ?)");
            $inserir->bind_param("ii", $filme_id, $usuario_id);
            $inserir->execute();
            $inserir->close();
            echo json_encode(['sucesso' => true, 'acao' => 'adicionado']);
        }
    } catch (Exception $erro) {
        registrarErro($_SESSION['id'], "Erro ao adicionar a minha lista!", $erro);
        echo json_encode(['sucesso' => false]);
    }
    exit;
}

echo json_encode(['sucesso' => false, 'erro' => 'Método inválido']);
exit;