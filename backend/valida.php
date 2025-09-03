<?php
require_once("conexao.php");

//Verifica se existe uma sessão ativa e se não houver inicia uma
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// verificando se tem cargo e caso não for adm impedindo as rotas de adm
if (!isset($_SESSION["cargo"]) || $_SESSION["cargo"] == 0) {
    $rota = $_GET['url'] ?? ''; // rota atual

    // rotas que o usuario comum pode acessar
    if ($rota != "filmes" &&
        $rota != "minha_lista" &&
        $rota != "buscar" &&
        $rota != "adicionar_minha_lista" &&
        $rota != "remover_minha_lista" &&
        $rota != "assistir" &&
        $rota != "info" &&
        $rota != "login" &&
        $rota != "cadastro" &&
        $rota != "perfil" &&
        $rota != "atualizar_perfil" &&
        $rota != "toggle_minha_lista" &&
        $rota != "procurar_filmes" &&
        $rota != ""
    ) {
        $_SESSION['resposta'] = "Acesso negado!";
        header("Location: " . BASE_URL . "filmes");
        exit();
    }
}

if (!isset($_SESSION["id"]) && !isset($_SESSION["nome"]) && !isset($_SESSION["email"])) {
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL);
    exit();
} else {
    $id = $_SESSION["id"];
    $stmt = $conexao->prepare("SELECT nome, email, cargo, img_perfil FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->bind_result($nome, $email, $cargo, $img_perfil);
        $stmt->fetch();
        $stmt->close();

        if ((($nome === null) || ($email === null) || ($cargo === null) || ($img_perfil === null))) {
            session_unset();
            session_destroy();
            header("Location: " . BASE_URL);
            exit();
        } else {
            $_SESSION["nome"] = $nome;
            $_SESSION["email"] = $email;
            $_SESSION["cargo"] = $cargo;

            // trocando o caminho conforme a imagem
            if ($img_perfil == 0) {
                $_SESSION["img_perfil"] = BASE_URL . "assets/img/perfil/macaco.webp";
            } elseif ($img_perfil == 1) {
                $_SESSION["img_perfil"] = BASE_URL . "assets/img/perfil/macaca.webp";
            } elseif ($img_perfil == 2) {
                $_SESSION["img_perfil"] = BASE_URL . "assets/img/perfil/macagay.webp";
            } else {
                $_SESSION["img_perfil"] = "https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png";
            }
        }
    } else {
        $_SESSION['resposta'] = "Erro inesperado!";
        header("Location: " . BASE_URL);
        exit();
    }
}