<?php
require __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Strings (removendo espaços e caracteres perigosos)
    $nome = trim(strip_tags($_POST['nome']));

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: " . BASE_URL . "categorias");
        exit;
    }
    try {
        $sql = "INSERT INTO categorias (nome) VALUES (?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $nome);

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Categoria cadastrada com sucesso!";
            header("Location: " . BASE_URL . "categorias");
            $stmt->close();
            exit;
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro!";
            header("Location: " . BASE_URL . "categorias");
            $stmt->close();
            exit;
        }
    } catch (Exception $erro) {
        registrarErro($_SESSION['id'], "Erro ao cadastrar categoria!", $erro);
        // Caso houver erro ele retorna
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: " . BASE_URL . "categorias");
                exit;
        }
    }
} else {
    $_SESSION['resposta'] = "Método de solicitação ínvalido!";
}

header("Location: " . BASE_URL . "categorias");
$stmt = null;
exit;