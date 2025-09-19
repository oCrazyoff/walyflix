<?php
session_start();
require_once __DIR__."/../funcoes/geral.php";
require_once __DIR__."/../conexao.php";

try {
    if (isset($_COOKIE['lembrar_me'])) {

        // seletor do cookie
        list($seletor, $verificador) = explode(':', $_COOKIE['lembrar_me'], 2);

        if ($seletor) {
            // Apaga o token do banco de dados
            $sql = "DELETE FROM tokens_autenticacao WHERE seletor = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("s", $seletor);
            $stmt->execute();
            $stmt->close();
        }

        // Apaga o cookie do navegador definindo uma data de expiração no passado
        setcookie('lembrar_me', '', time() - 3600, '/');
    }

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