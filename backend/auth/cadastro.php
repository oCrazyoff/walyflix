<?php
session_start();
require_once("funcoes.php");
require __DIR__ . '/../conexao.php';

// Apenas processa se o método for POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim(strip_tags($_POST['nome']));
    $email = trim(strip_tags($_POST['email']));
    $senha = trim($_POST["senha"]);
    $confirmar_senha = trim($_POST["confirmar-senha"]);
    $csrf = isset($_POST["csrf"]) ? trim(strip_tags($_POST["csrf"])) : '';


    // Verificar token CSRF para segurança contra ataques
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Erro de segurança. Tente novamente.";
        header("Location: " . BASE_URL . "cadastro");
        exit;
    }

    // Validar o nome
    $nome = validarNome($nome);

    // Validar o email
    if (validarEmail($email) == false) {
        $_SESSION['resposta'] = "Email com formato inválido!";
        header("Location: " . BASE_URL . "cadastro");
        exit;
    }

    // formata para tudo ficar minusculo
    $email = mb_convert_case($email, MB_CASE_LOWER, "UTF-8");

    // Validar se as senhas coincidem
    if ($senha !== $confirmar_senha) {
        $_SESSION['resposta'] = "As senhas não coincidem!";
        header("Location: " . BASE_URL . "cadastro");
        exit;
    }

    // Validar a complexidade da senha (usando sua função)
    if (validarSenha($senha) == false) {
        $_SESSION['resposta'] = "Senha inválida!";
        header("Location: " . BASE_URL . "cadastro");
        exit;
    }

    try {
        // verificando se o email ja existe
        $sql_check = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_check = $conexao->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $_SESSION['resposta'] = "Este email já está cadastrado!";
            $stmt_check->close();
            header("Location: " . BASE_URL . "cadastro");
            exit;
        }
        $stmt_check->close();


        // inserindo o novo usuario
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $cargo_padrao = 0;

        $sql_insert = "INSERT INTO usuarios (nome, email, cargo, senha_hash) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conexao->prepare($sql_insert);
        $stmt_insert->bind_param("ssis", $nome, $email, $cargo_padrao, $senha_hash);

        if ($stmt_insert->execute()) {
            $_SESSION['resposta'] = "Cadastro realizado com sucesso! Faça o login.";
            header("Location: " . BASE_URL . "login");
            $stmt_insert->close();
            exit;
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao criar a conta. Tente novamente.";
            header("Location: " . BASE_URL . "cadastro");
            $stmt_insert->close();
            exit;
        }
    } catch (Exception $erro) {
        // Registra o erro para o admin, mas mostra uma mensagem genérica para o usuário
        registrarErro($_SESSION['id'] ?? 0, "Erro no cadastro de usuário público!", $erro);
        $_SESSION['resposta'] = "Ocorreu um erro inesperado no servidor. Tente mais tarde.";
        header("Location: " . BASE_URL . "cadastro");
        exit;
    }
} else {
    // Se alguém tentar acessar o arquivo diretamente sem usar o formulário
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "cadastro");
    exit;
}