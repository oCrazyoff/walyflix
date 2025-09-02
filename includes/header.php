<header class="flex sticky top-0 left-0 items-center justify-center w-full h-[5rem] p-5 border-b bg-cinza/80 backdrop-blur-lg  border-cinza-claro z-700">
    <div class=" interface flex justify-between items-center">
        <div class="flex items-center justify-center gap-2">
            <h1 class="logo mr-5">Waly<span>Flix</span></h1>
            <nav class="flex items-center justify-center gap-5">
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
            </nav>
        </div>
        <a class="flex items-center justify-center rounded-md overflow-hidden p-1  w-12 h-12 hover:ring"
           href="perfil">
            <img src="<?= htmlspecialchars($_SESSION['img_perfil']) ?>" class="w-full h-full object-cover rounded-md"
                 alt="Imagem de perfil">
        </a>
    </div>
</header>