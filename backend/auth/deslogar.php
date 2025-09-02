<?php
session_start();
require_once __DIR__."/../funcoes/geral.php";
require_once __DIR__."/../conexao.php";

try {
    // limpa todas as variáveis da sessão
    $_SESSION = [];

    // destrói a sessão
    session_destroy();

    // mensagem de saída
    session_start();
    $_SESSION['resposta'] = "Você saiu da sua conta com sucesso!";

    header("Location: " . BASE_URL . "login");
    exit;

} catch (Exception $erro) {
    // registra erro caso algo dê errado
    registrarErro(null, "Erro ao deslogar usuário!", $erro);
    $_SESSION['resposta'] = "Erro inesperado ao sair da conta.";
    header("Location: " . BASE_URL . "login");
    exit;
}
