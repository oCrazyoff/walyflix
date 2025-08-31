<?php
require __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Número inteiro
    $filme_id = filter_input(INPUT_POST, 'filme_id', FILTER_VALIDATE_INT);
    $usuario_id = $_SESSION['id'];

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: " . BASE_URL . "info?filme=" . htmlspecialchars($filme_id));
        exit;
    }

    try {
        $sql = "INSERT INTO minha_lista (usuario_id, filme_id) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $usuario_id, $filme_id);

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Filme adicionado com sucesso!";
            header("Location: " . BASE_URL . "info?filme=" . htmlspecialchars($filme_id));
            $stmt->close();
            exit;
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro!";
            header("Location: " . BASE_URL . "info?filme=" . htmlspecialchars($filme_id));
            $stmt->close();
            exit;
        }
    } catch (Exception $erro) {
        registrarErro($_SESSION['id'], "Erro ao adicionar a minha lista!", $erro);
        // Caso houver erro ele retorna
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: " . BASE_URL . "info?filme=" . htmlspecialchars($filme_id));
                exit;
        }
    }
} else {
    $_SESSION['resposta'] = "Método de solicitação ínvalido!";
}

header("Location: " . BASE_URL . "filmes");
$stmt = null;
exit;