<?php
// caso o usuario for adm
if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 1):
    ?>
    <a class="link-menu <?= ($rota === 'dashboard') ? 'ativo' : '' ?>" href="dashboard"><i
                class="bi bi-columns-gap"></i> Dashboard</a>
    <a class="link-menu <?= ($rota === 'filmes_adm') ? 'ativo' : '' ?>" href="filmes_adm"><i
                class="bi bi-collection-play"></i> Gerenciar Filmes</a>
    <a class="link-menu <?= ($rota === 'usuarios') ? 'ativo' : '' ?>" href="usuarios"><i
                class="bi bi-people"></i> Usuários</a>
    <a class="link-menu <?= ($rota === 'categorias') ? 'ativo' : '' ?>" href="categorias"><i
                class="bi bi-tags"></i> Categorias</a>
<?php endif; ?>

<a class="link-menu <?= ($rota === 'filmes') ? 'ativo' : '' ?>" href="filmes"><i class="bi bi-film"></i>
    Filmes</a>
<a class="link-menu <?= ($rota === 'minha_lista') ? 'ativo' : '' ?>" href="minha_lista"><i
            class="bi bi-bookmark-star"></i> Minha Lista</a>
<a class="link-menu <?= ($rota === 'buscar') ? 'ativo' : '' ?>" href="buscar"><i
            class="bi bi-search"></i> Buscar</a>