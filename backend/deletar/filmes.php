<?php
require __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    // Se o ID for inválido ou não existir, interrompe o processo
    if (!$id) {
        $_SESSION['resposta'] = "ID do filme é inválido!";
        header("Location: " . BASE_URL . "filmes-adm");
        exit;
    }

    // verifica o token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token de segurança inválido!";
        header("Location: " . BASE_URL . "filmes-adm");
        exit;
    }

    try {
        $sql = "DELETE FROM filmes WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['resposta'] = "Filme deletado com sucesso!";
            } else {
                $_SESSION['resposta'] = "Nenhum filme encontrado.";
            }
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao tentar deletar o filme!";
        }

        $stmt->close();

    } catch (Exception $erro) {
        // Registra o erro para análise posterior
        registrarErro($_SESSION['id'], "Erro ao deletar filme com ID: $id", $erro);
        $_SESSION['resposta'] = "Erro inesperado.";
    }

    header("Location: " . BASE_URL . "filmes-adm");
    exit;

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "filmes-adm");
    exit;
}