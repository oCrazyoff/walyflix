<header class="flex items-center justify-center w-full h-[5rem] p-5 border-b border-cinza-claro">
    <div class=" interface flex justify-between items-center">
        <div class="flex items-center justify-center gap-2">
            <h1 class="logo mr-5">Waly<span>Flix</span></h1>
            <nav class="flex items-center justify-center gap-5">
                <?php
                // caso o usuario for adm
                if (isset($_SESSION['cargo']) && $_SESSION['cargo'] == 1):
                    ?>
                    <a class="link-menu" href="dashboard"><i class="bi bi-columns-gap"></i> Dashboard</a>
                    <a class="link-menu" href="filmes-adm"><i class="bi bi-collection-play"></i> Gerenciar Filmes</a>
                    <a class="link-menu" href="usuarios"><i class="bi bi-people"></i> Usuários</a>
                    <a class="link-menu" href="categorias"><i class="bi bi-tags"></i> Categorias</a>
                <?php endif; ?>
                <a class="link-menu" href="filmes"><i class="bi bi-film"></i> Filmes</a>
                <a class="link-menu" href="minha-lista"><i class="bi bi-bookmark-star"></i> Minha Lista</a>
                <a class="link-menu" href="buscar"><i class="bi bi-search"></i> Buscar</a>
            </nav>
        </div>
        <a class="flex items-center justify-center rounded-md p-2  w-10 h-10 text-3xl hover:bg-cinza-claro" href="#">
            <i class="bi bi-person-circle"></i>
        </a>
    </div>
</header>