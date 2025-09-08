<?php
require __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Strings (removendo espaços e caracteres perigosos)
    $titulo = trim(strip_tags($_POST['titulo']));
    $descricao = trim(strip_tags($_POST['descricao']));

    // Número inteiro
    $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);

    // URLs (remove caracteres inválidos)
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

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: " . BASE_URL . "filmes_adm");
        exit;
    }
    try {
        $sql = "INSERT INTO filmes (titulo, descricao, ano, categoria_id, imagem_url, imagem_deitada_url, link_filme) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssiisss", $titulo, $descricao, $ano, $categoria_id, $imagem_url, $imagem_deitada_url, $link_filme);

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Filme cadastrado com sucesso!";
            header("Location: " . BASE_URL . "filmes_adm");
            $stmt->close();
            exit;
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro!";
            header("Location: " . BASE_URL . "filmes_adm");
            $stmt->close();
            exit;
        }
    } catch (Exception $erro) {
        registrarErro($_SESSION['id'], "Erro ao cadastrar filme!", $erro);
        // Caso houver erro ele retorna
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: " . BASE_URL . "filmes_adm");
                exit;
        }
    }
} else {
    $_SESSION['resposta'] = "Método de solicitação ínvalido!";
}

header("Location: " . BASE_URL . "filmes_adm");
$stmt = null;
exit;