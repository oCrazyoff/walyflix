<?php
$url = $_GET['url'] ?? '';
$url = trim($url, '/');

// rotas simples
$routes = [
    '' => 'index.php',

    // autenticação
    'login' => 'auth/login.php',
    'cadastro' => 'auth/cadastro.php',

    //rotas do usuario comum
    'filmes' => 'pages/filmes.php',
    'minha-lista' => 'pages/minha_lista.php',
    'buscar' => 'pages/buscar.php',

    // rotas do adm
    'dashboard' => 'adm/dashboard.php',
    'filmes-adm' => 'adm/filmes_adm.php',
    'usuarios' => 'adm/usuarios.php',
    'categorias' => 'adm/categorias.php',
];

if (array_key_exists($url, $routes)) {
    require $routes[$url];
    exit;
}

// rotas dinâmicas
// ...
http_response_code(404);
echo "Página não encontrada!";
