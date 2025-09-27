<?php
session_start();
require_once("funcoes.php");
require __DIR__ . '/../conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim(strip_tags($_POST['email']));
    $senha = trim($_POST["senha"]);

    // Verificar o email
    if (validarEmail($email) == false) {
        $_SESSION['resposta'] = "Email inválido!";
        header("Location: " . BASE_URL . "login");
        exit;
    }

    //Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Método invalido!";
        header("Location: " . BASE_URL . "login");
        exit;
    }

    //Validadar senha
    if (validarSenha($senha) == false) {
        $_SESSION['resposta'] = "Senha inválida!";
        header("Location: " . BASE_URL . "login");
        exit;
    }

    if (!empty($email) && !empty($senha)) {
        try {
            // Faz a verificação no banco de dados
            $stmt = $conexao->prepare("SELECT id, nome, email, senha_hash, img_perfil, cargo FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id, $nome, $email, $senha_db, $img_perfil, $cargo);

            $usuarioEncontrado = $stmt->fetch();
            $stmt->close();

            if (!$usuarioEncontrado) {
                $_SESSION['resposta'] = "E-mail ou senha incorretos!";
                header("Location: " . BASE_URL . "login");
                exit;
            }

            // verifica se a senha esta correta
            if (password_verify($senha, $senha_db)) {

                // atualiza as variaveis sessions
                $_SESSION["id"] = $id;
                $_SESSION["nome"] = $nome;
                $_SESSION["email"] = $email;
                $_SESSION["cargo"] = $cargo;

                // trocando o caminho da imagem de perfil conforme o banco
                if ($img_perfil == 0) {
                    $_SESSION["img_perfil"] = BASE_URL . "assets/img/perfil/macaco.webp";
                } elseif ($img_perfil == 1) {
                    $_SESSION["img_perfil"] = BASE_URL . "assets/img/perfil/macaca.webp";
                } elseif ($img_perfil == 2) {
                    $_SESSION["img_perfil"] = BASE_URL . "assets/img/perfil/macagay.webp";
                } else {
                    $_SESSION["img_perfil"] = "https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png";
                }

                $_SESSION['resposta'] = "Bem Vindo! " . $_SESSION['nome'];

                if ($cargo == 0) {
                    // caso o usuario for comum
                    header("Location: " . BASE_URL . "filmes");
                    exit;
                } elseif ($cargo == 1) {
                    // caso o usuario for adm
                    header("Location: " . BASE_URL . "dashboard");
                    exit;
                }
            } else {
                $_SESSION['resposta'] = "E-mail ou senha incorretos!";
                header("Location: " . BASE_URL . "login");
                exit;
            }
        } catch (Exception $erro) {
            registrarErro(null, "Erro ao logar usuario!", $erro);
            // Caso houver erro ele retorna
            switch ($erro->getCode()) {
                // erro de quantidade de paramêtros erro
                case 1136:
                    $_SESSION['resposta'] = "Quantidade de dados inseridos inválida!";
                    header("Location: " . BASE_URL . "login");
                    exit;
                default:
                    $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                    header("Location: " . BASE_URL . "login");
                    exit;
            }
        }
    } else {
        $_SESSION['resposta'] = "Preencha todas as informações!";
    }
} else {
    $_SESSION['resposta'] = "Variável POST ínvalida!";
}
header("Location: " . BASE_URL . "login");
exit;