<?php
require __DIR__ . '/../valida.php';
require __DIR__ . '/../auth/funcoes.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_SESSION['id'];
    $nome = trim(strip_tags($_POST['nome']));
    $email = trim(strip_tags($_POST['email']));
    $senha = trim($_POST["senha"]);
    $nova_senha = trim($_POST["nova-senha"]);
    $img_perfil = filter_input(INPUT_POST, 'img-perfil', FILTER_VALIDATE_INT);

    // Se o ID for inválido ou não existir, interrompe o processo
    if (!$id) {
        $_SESSION['resposta'] = "ID do usuário é inválido!";
        header("Location: " . BASE_URL . "perfil");
        exit;
    }

    // validar o nome
    $nome = validarNome($nome);

    // Verificar o email
    if (validarEmail($email) == false) {
        $_SESSION['resposta'] = "Email inválido!";
        header("Location: " . BASE_URL . "perfil");
        exit;
    }

    if (!empty($senha) && !empty($nova_senha)) {
        // verificando se a senha atual é a correta
        $sql = "SELECT senha_hash FROM usuarios WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($senha_db);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($senha, $senha_db)) {
            //validar a nova senha
            if (validarSenha($nova_senha) == false) {
                $_SESSION['resposta'] = "Nova senha inválida!";
                header("Location: " . BASE_URL . "perfil");
                exit;
            }

            // criptografar senha
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        } else {
            $_SESSION['resposta'] = "Senha incorreta!";
            header("Location: " . BASE_URL . "perfil");
            exit;
        }
    }

    // verificar o token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token de segurança inválido!";
        header("Location: " . BASE_URL . "perfil");
        exit;
    }

    try {
        if (!empty($senha) && !empty($nova_senha)) {
            $sql = "UPDATE usuarios SET nome = ?, email = ?, cargo = ?, senha_hash = ?, img_perfil = ? WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ssissi", $nome, $email, $cargo, $senha_hash, $img_perfil, $id);
        } else {
            $sql = "UPDATE usuarios SET nome = ?, email = ?, cargo = ?, img_perfil = ? WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ssisi", $nome, $email, $cargo, $img_perfil, $id);
        }

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Perfil atualizado com sucesso!";
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao atualizar o perfil!";
        }

        $stmt->close();

    } catch (Exception $erro) {
        // Registra o erro para análise posterior
        registrarErro($_SESSION['id'], "Erro ao editar perfil com ID: $id", $erro);
        $_SESSION['resposta'] = "Erro inesperado. Contate o suporte.";
    }

    header("Location: " . BASE_URL . "perfil");
    exit;

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "perfil");
    exit;
}