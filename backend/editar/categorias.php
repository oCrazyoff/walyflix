<?php
require __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    // Se o ID for inválido ou não existir, interrompe o processo
    if (!$id) {
        $_SESSION['resposta'] = "ID da categoria é inválido!";
        header("Location: " . BASE_URL . "categorias");
        exit;
    }

    $nome = trim(strip_tags($_POST['nome']));

    // verificar o token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token de segurança inválido!";
        header("Location: " . BASE_URL . "categorias");
        exit;
    }

    try {
        $sql = "UPDATE categorias SET nome = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("si", $nome, $id);

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Categoria atualizada com sucesso!";
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao atualizar a categoria!";
        }

        $stmt->close();

    } catch (Exception $erro) {
        // Registra o erro para análise posterior
        registrarErro($_SESSION['id'], "Erro ao editar categoria com ID: $id", $erro);
        $_SESSION['resposta'] = "Erro inesperado. Contate o suporte.";
    }

    header("Location: " . BASE_URL . "categorias");
    exit;

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "categorias");
    exit;
}