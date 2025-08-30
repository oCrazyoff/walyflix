<?php
require_once("conexao.php");

//Verifica se existe uma sessão ativa e se não houver inicia uma
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION["id"]) && !isset($_SESSION["nome"]) && !isset($_SESSION["email"])) {
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL);
    exit();
} else {
    $email = $_SESSION["email"];
    $stmt = $conexao->prepare("SELECT nome, email, cargo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $stmt->bind_result($nome, $email, $cargo);
        $stmt->fetch();
        $stmt->close();

        if ((($nome === null) || ($email === null) || ($cargo === null))) {
            session_unset();
            session_destroy();
            header("Location: " . BASE_URL);
            exit();
        } else {
            $_SESSION["nome"] = $nome;
            $_SESSION["email"] = $email;
            $_SESSION["cargo"] = $cargo;
        }
    }
}