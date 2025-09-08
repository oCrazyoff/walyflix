<?php
$url = $_GET['url'] ?? '';
$url = trim($url, '/');

// rotas simples
$routes = [
    '' => 'pages/landing.php',

    // autenticação
    'login' => 'auth/login.php',
    'cadastro' => 'auth/cadastro.php',
    'deslogar' => 'backend/auth/deslogar.php',
    'fazer_login' => 'backend/auth/login.php',
    'fazer_cadastro' => 'backend/auth/cadastro.php',

    //rotas do usuario comum
    'filmes' => 'pages/filmes.php',
    'minha_lista' => 'pages/minha_lista.php',
    'buscar' => 'pages/buscar.php',
    'perfil' => 'pages/perfil.php',

    // rotas do adm
    'dashboard' => 'adm/dashboard.php',
    'filmes_adm' => 'adm/filmes_adm.php',
    'usuarios' => 'adm/usuarios.php',
    'categorias' => 'adm/categorias.php',

    // rotas de busca
    'buscar_filmes' => 'backend/buscar/filmes.php',
    'procurar_filmes' => 'backend/buscar/procurar_filmes.php',
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
    'atualizar_perfil' => 'backend/editar/perfil.php',

    // rotas de deletar
    'deletar_filmes' => 'backend/deletar/filmes.php',
    'deletar_categorias' => 'backend/deletar/categorias.php',
    'deletar_usuarios' => 'backend/deletar/usuarios.php',

    // rotas especificas
    'filme_destaque' => 'backend/editar/filme_destaque.php',
    'info' => 'pages/info.php',
    'assistir' => 'pages/assistir.php',
    'toggle_minha_lista' => 'backend/minha_lista/toggle.php',
];

if (array_key_exists($url, $routes)) {
    require $routes[$url];
    exit;
}

http_response_code(404);
echo "Página não encontrada!";
