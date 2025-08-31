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

    // rotas de busca
    'buscar_filmes' => 'backend/buscar/filmes.php',
    'buscar_categorias' => 'backend/buscar/categorias.php',
    'buscar_usuarios' => 'backend/buscar/usuarios.php',

    // rotas de cadastro
    'cadastrar_filmes' => 'backend/cadastrar/filmes.php',
    'cadastrar_categorias' => 'backend/cadastrar/categorias.php',
    'cadastrar_usuarios' => 'backend/cadastrar/usuarios.php',

    // rotas de edição
    'editar_filmes' => 'backend/editar/filmes.php',
    'editar_categorias' => 'backend/editar/categorias.php',
    'editar_usuarios' => 'backend/editar/usuarios.php',

    // rotas de deletar
    'deletar_filmes' => 'backend/deletar/filmes.php',
    'deletar_categorias' => 'backend/deletar/categorias.php',
    'deletar_usuarios' => 'backend/deletar/usuarios.php',

    // rotas especificas
    'filme_destaque' => 'backend/editar/filme_destaque.php',
];

if (array_key_exists($url, $routes)) {
    require $routes[$url];
    exit;
}

// rotas dinâmicas
// ...
http_response_code(404);
echo "Página não encontrada!";
