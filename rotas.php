<?php
$url = $_GET['url'] ?? '';
$url = trim($url, '/');

// rotas simples
$routes = [
    '' => 'index.php',

    // autenticação
    'login' => 'auth/login.php',
    'cadastro' => 'auth/cadastro.php',

    // rotas do adm
    'dashboard' => 'adm/dashboard.php',
];

if (array_key_exists($url, $routes)) {
    require $routes[$url];
    exit;
}

// rotas dinâmicas
// ...
http_response_code(404);
echo "Página não encontrada!";
