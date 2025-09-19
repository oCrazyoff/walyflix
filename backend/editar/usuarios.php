<?php
require __DIR__ . '/../valida.php';
require __DIR__ . '/../auth/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    // Se o ID for inválido ou não existir, interrompe o processo
    if (!$id) {
        $_SESSION['resposta'] = "ID do usuário é inválido!";
        header("Location: " . BASE_URL . "usuarios");
        exit;
    }

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

    // formatando tudo para minusculo
    $email = mb_convert_case($email, MB_CASE_LOWER, "UTF-8");

    if (!empty($senha)) {
        //Validar senha
        if (validarSenha($senha) == false) {
            $_SESSION['resposta'] = "Senha inválida!";
            header("Location: " . BASE_URL . "usuarios");
            exit;
        }

        // criptografar senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    }

    // verificar o token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token de segurança inválido!";
        header("Location: " . BASE_URL . "usuarios");
        exit;
    }

    try {
        if (!empty($senha)) {
            $sql = "UPDATE usuarios SET nome = ?, email = ?, cargo = ?, senha_hash = ? WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ssisi", $nome, $email, $cargo, $senha_hash, $id);
        } else {
            $sql = "UPDATE usuarios SET nome = ?, email = ?, cargo = ? WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ssii", $nome, $email, $cargo, $id);
        }

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Usuário atualizado com sucesso!";
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao atualizar o usuário!";
        }

        $stmt->close();

    } catch (Exception $erro) {
        // Registra o erro para análise posterior
        registrarErro($_SESSION['id'], "Erro ao editar usuário com ID: $id", $erro);
        $_SESSION['resposta'] = "Erro inesperado. Contate o suporte.";
    }

    header("Location: " . BASE_URL . "usuarios");
    exit;

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "usuarios");
    exit;
}