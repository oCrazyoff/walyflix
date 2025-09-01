<?php
require __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Número inteiro
    $filme_id = filter_input(INPUT_POST, 'filme_id', FILTER_VALIDATE_INT);
    $usuario_id = $_SESSION['id'];

    // Define a URL padrão de redirecionamento
    if (!empty($_POST['minha_lista'])) {
        $redirecionamento = BASE_URL . "minha_lista";
    } elseif (!empty($_POST['destaque'])) {
        $redirecionamento = BASE_URL . "filmes";
    } elseif (!empty($_POST['info'])) {
        $redirecionamento = BASE_URL . "info?filme=" . $filme_id;
    } else {
        $redirecionamento = BASE_URL . "filmes";
    }

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: $redirecionamento");
        exit;
    }

    try {
        $sql = "INSERT INTO minha_lista (usuario_id, filme_id) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $usuario_id, $filme_id);

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Filme adicionado com sucesso!";
            header("Location: $redirecionamento");
            $stmt->close();
            exit;
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro!";
            header("Location: $redirecionamento");
            $stmt->close();
            exit;
        }
    } catch (Exception $erro) {
        registrarErro($_SESSION['id'], "Erro ao adicionar a minha lista!", $erro);
        // Caso houver erro ele retorna
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: $redirecionamento");
                exit;
        }
    }
} else {
    $_SESSION['resposta'] = "Método de solicitação ínvalido!";
}

header("Location: " . BASE_URL . "filmes");
$stmt = null;
exit;