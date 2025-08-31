<?php
require __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        $_SESSION['resposta'] = "ID do filme é inválido!";
        header("Location: " . BASE_URL . "filmes_adm");
        exit;
    }

    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token de segurança inválido!";
        header("Location: " . BASE_URL . "filmes_adm");
        exit;
    }

    $conexao->begin_transaction();

    try {
        $sqlReset = "UPDATE filmes SET destaque = 0";
        $stmtReset = $conexao->prepare($sqlReset);
        $stmtReset->execute();
        $stmtReset->close();

        $sqlDestaque = "UPDATE filmes SET destaque = 1 WHERE id = ?";
        $stmtDestaque = $conexao->prepare($sqlDestaque);
        $stmtDestaque->bind_param("i", $id);
        $stmtDestaque->execute();

        if ($stmtDestaque->affected_rows > 0) {
            // Se tudo deu certo até aqui, confirma as alterações no banco
            $conexao->commit();
            $_SESSION['resposta'] = "Filme destacado com sucesso!";
        } else {
            // Se o filme com o ID fornecido não foi encontrado, desfaz tudo
            $conexao->rollback();
            $_SESSION['resposta'] = "Filme não encontrado para ser destacado.";
        }

        $stmtDestaque->close();

    } catch (Exception $erro) {
        // Se qualquer um dos comandos falhar, desfaz TODAS as alterações
        $conexao->rollback();

        // Registra o erro para análise
        registrarErro($_SESSION['id'], "Erro ao destacar filme com ID: $id", $erro);
        $_SESSION['resposta'] = "Erro inesperado ao destacar o filme.";
    }

    header("Location: " . BASE_URL . "filmes_adm");
    exit;

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "filmes_adm");
    exit;
}