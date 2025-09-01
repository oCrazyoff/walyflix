<?php
require __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $usuario_id = $_SESSION['id'];

    // Define a URL padrão de redirecionamento
    if (!empty($_POST['minha_lista'])) {
        $redirecionamento = BASE_URL . "minha_lista";
    } elseif (!empty($_POST['destaque'])) {
        $redirecionamento = BASE_URL . "filmes";
    } elseif (!empty($_POST['info'])) {
        $redirecionamento = BASE_URL . "info?filme=" . $id;
    } else {
        $redirecionamento = BASE_URL . "filmes";
    }

    // Se o ID for inválido ou não existir, interrompe o processo
    if (!$id) {
        $_SESSION['resposta'] = "ID do filme é inválido!";
        header("Location: $redirecionamento");
        exit;
    }

    // verifica o token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token de segurança inválido!";
        header("Location: $redirecionamento");
        exit;
    }

    try {
        $sql = "DELETE FROM minha_lista WHERE filme_id = ? AND usuario_id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $id, $usuario_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['resposta'] = "Filme removido com sucesso!";
            } else {
                $_SESSION['resposta'] = "Nenhum filme encontrado.";
            }
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao tentar remover o filme!";
        }

        $stmt->close();

    } catch (Exception $erro) {
        // Registra o erro para análise posterior
        registrarErro($_SESSION['id'], "Erro ao remover da lista o filme com ID: $id", $erro);
        $_SESSION['resposta'] = "Erro inesperado.";
    }

    // Redireciona no final
    header("Location: $redirecionamento");
    exit;

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "filmes");
    exit;
}