<?php
require __DIR__ . '/../valida.php';
require __DIR__ . '/../auth/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Strings (removendo espaços e caracteres perigosos)
    $nome = trim(strip_tags($_POST['nome']));
    $email = trim(strip_tags($_POST['email']));
    $cargo = filter_input(INPUT_POST, 'cargo', FILTER_VALIDATE_INT);
    $senha = trim($_POST["senha"]);

    // validar o nome
    $nome = validarNome($nome);

    // Verificar o email
    if (validarEmail($email) == false) {
        $_SESSION['resposta'] = "Email inválido!";
        header("Location: " . BASE_URL . "usuarios");
        exit;
    }

    //Validadar senha
    if (validarSenha($senha) == false) {
        $_SESSION['resposta'] = "Senha inválida!";
        header("Location: " . BASE_URL . "usuarios");
        exit;
    }

    // criptografar senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: " . BASE_URL . "categorias");
        exit;
    }
    try {
        $sql = "INSERT INTO usuarios (nome, email, cargo, senha_hash) VALUES (?,?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssis", $nome, $email, $cargo, $senha_hash);

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Usuário cadastrado com sucesso!";
            header("Location: " . BASE_URL . "usuarios");
            $stmt->close();
            exit;
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro!";
            header("Location: " . BASE_URL . "usuarios");
            $stmt->close();
            exit;
        }
    } catch (Exception $erro) {
        registrarErro($_SESSION['id'], "Erro ao cadastrar usuário!", $erro);
        // Caso houver erro ele retorna
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: " . BASE_URL . "usuarios");
                exit;
        }
    }
} else {
    $_SESSION['resposta'] = "Método de solicitação ínvalido!";
}

header("Location: " . BASE_URL . "usuarios");
$stmt = null;
exit;