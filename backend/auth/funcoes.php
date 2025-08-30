<?php
function validarNome($nome)
{
    // Remove espaços no início/fim
    $nome = trim($nome);

    // Regex: letras (com acento), espaço, mínimo 3 e máximo 50 caracteres
    if (!preg_match('/^[\p{L} ]{3,50}$/u', $nome)) {
        return false;
    }

    // Formata para primeira letra maiúscula em cada palavra
    $nome = mb_convert_case($nome, MB_CASE_TITLE, "UTF-8");

    return $nome;
}

function validarEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}


function validarSenha($senha)
{
    // Pelo menos 8 caracteres, uma letra maiúscula, uma letra minúscula, um número e um caractere especial
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $senha)) {
        return false;
    }

    return true;
}

?>