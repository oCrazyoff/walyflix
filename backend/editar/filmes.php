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

    $titulo = trim(strip_tags($_POST['titulo']));
    $descricao = trim(strip_tags($_POST['descricao']));
    $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
    $imagem_url = trim(filter_input(INPUT_POST, 'imagem_url', FILTER_SANITIZE_URL));
    $imagem_deitada_url = trim(filter_input(INPUT_POST, 'imagem_deitada_url', FILTER_SANITIZE_URL));
    $link_filme = trim(filter_input(INPUT_POST, 'link_filme', FILTER_SANITIZE_URL));
    $link_filme = ajustarDropboxLink($link_filme);

    //validar imagem
    if (validarURL($imagem_url) == false) {
        $_SESSION['resposta'] = "Imagem inválida.";
        header("Location: " . BASE_URL . "filmes_adm");
        exit;
    }

    //validar imagem deitada
    if (validarURL($imagem_deitada_url) == false) {
        $_SESSION['resposta'] = "Imagem deitada inválida.";
        header("Location: " . BASE_URL . "filmes_adm");
        exit;
    }

    //validar link filme
    if (validarURL($link_filme) == false) {
        $_SESSION['resposta'] = "Filme inválido.";
        header("Location: " . BASE_URL . "filmes_adm");
        exit;
    }

    // verificar o token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token de segurança inválido!";
        header("Location: " . BASE_URL . "filmes_adm");
        exit;
    }

    try {
        $sql = "UPDATE filmes SET titulo = ?, descricao = ?, ano = ?, categoria_id = ?, imagem_url = ?, imagem_deitada_url = ?, link_filme = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssiisssi", $titulo, $descricao, $ano, $categoria_id, $imagem_url, $imagem_deitada_url, $link_filme, $id);

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Filme atualizado com sucesso!";
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao atualizar o filme!";
        }

        $stmt->close();

    } catch (Exception $erro) {
        // Registra o erro para análise posterior
        registrarErro($_SESSION['id'], "Erro ao editar filme com ID: $id", $erro);
        $_SESSION['resposta'] = "Erro inesperado. Contate o suporte.";
    }

    header("Location: " . BASE_URL . "filmes_adm");
    exit;

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "filmes_adm");
    exit;
}