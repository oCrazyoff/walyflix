<?php
// Função para carregar variáveis do .env
function carregarEnv($caminho)
{
    if (!file_exists($caminho)) {
        throw new Exception("Arquivo .env não encontrado em: " . $caminho);
    }

    $linhas = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        // Ignorar comentários
        if (strpos(trim($linha), '#') === 0) {
            continue;
        }

        // Separar chave e valor
        list($chave, $valor) = explode('=', $linha, 2);

        $chave = trim($chave);
        $valor = trim($valor, " \"'"); // já remove espaços e aspas

        // Salvar no ambiente
        putenv("$chave=$valor");
        $_ENV[$chave] = $valor;
    }
}

// função para definir o BASE_URL
if (!defined('BASE_URL')) {
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define('BASE_URL', '/walyflix/');
    } else {
        define('BASE_URL', '/');
    }
}

function gerarCSRF()
{
    $_SESSION["csrf"] = (isset($_SESSION["csrf"])) ? $_SESSION["csrf"] : hash('sha256', random_bytes(32));

    return ($_SESSION["csrf"]);
}

function validarCSRF($csrf)
{
    if (!isset($_SESSION["csrf"])) {
        return (false);
    }
    if ($_SESSION["csrf"] !== $csrf) {
        return false;
    }
    if (!hash_equals($_SESSION["csrf"], $csrf)) {
        return false;
    }

    return true;
}

function registrarErro($usuario, $mensagem, $erro)
{
    global $conexao;

    $stmt = $conexao->prepare("INSERT INTO erros (usuario_id, mensagem, erro) VALUES (?,?,?)");
    $stmt->bind_param("iss", $usuario, $mensagem, $erro);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function validarURL(string $url): bool
{
    $url = trim($url);

    // Valida a URL usando FILTER_VALIDATE_URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    return true;
}

function ajustarDropboxLink($url)
{
    if (strpos($url, "dropbox.com") !== false) {
        // Se o link já tiver "raw=1", retorna direto
        if (strpos($url, "raw=1") !== false) {
            return $url;
        }
        // Troca dl=0 ou dl=1 por raw=1
        $url = preg_replace('/(\?|&)dl=\d/', '$1raw=1', $url);

        // Se ainda não tiver raw, adiciona
        if (strpos($url, "raw=1") === false) {
            $separador = (strpos($url, '?') !== false) ? '&' : '?';
            $url .= $separador . "raw=1";
        }
    }
    return $url;
}

function tentarLoginAutomatico()
{
    global $conexao;

    // A função só executa se o usuário NÃO tiver uma sessão e TIVER um cookie
    if (!isset($_SESSION["id"]) && isset($_COOKIE['lembrar_me'])) {
        // Separa o seletor e o verificador
        list($seletor, $verificador) = explode(':', $_COOKIE['lembrar_me'], 2);

        if ($seletor && $verificador) {
            $sql_token = "SELECT * FROM tokens_autenticacao WHERE seletor = ?";
            $stmt_token = $conexao->prepare($sql_token);
            $stmt_token->bind_param("s", $seletor);
            $stmt_token->execute();
            $resultado = $stmt_token->get_result();
            $token_info = $resultado->fetch_assoc();
            $stmt_token->close();

            // Se o token existe, é válido e não expirou...
            if ($token_info && hash_equals($token_info['verificador_hashed'], hash('sha256', $verificador)) && (strtotime($token_info['expira_em']) > time())) {

                // Busca os dados mais recentes do usuário
                $id_usuario = $token_info['usuario_id'];
                $stmt_usuario = $conexao->prepare("SELECT nome, email, cargo, img_perfil FROM usuarios WHERE id = ?");
                $stmt_usuario->bind_param("i", $id_usuario);
                $stmt_usuario->execute();
                $stmt_usuario->bind_result($nome, $email, $cargo, $img_perfil);

                if ($stmt_usuario->fetch()) {
                    // Recria a sessão para o usuário
                    $_SESSION["id"] = $id_usuario;
                    $_SESSION["nome"] = $nome;
                    $_SESSION["email"] = $email;
                    $_SESSION["cargo"] = $cargo;
                    // A imagem será carregada pelo valida.php ou outra lógica
                    $stmt_usuario->close();
                    return true; // Login bem-sucedido
                }
                $stmt_usuario->close();
            }
        }
    }
    return false; // Não foi possível logar automaticamente
}

?>