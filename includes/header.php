<header class="flex sticky top-0 left-0 items-center justify-center w-full h-[5rem] p-0 lg:p-5 border-b bg-cinza/80 backdrop-blur-lg  border-borda z-700">
    <div class=" interface flex justify-between items-center">
        <div class="flex items-center justify-center gap-2">
            <h1 class="logo mr-5">Waly<span>Flix</span></h1>
            <nav class="hidden lg:static flex items-center justify-center gap-5">
                <?php include("links_nav.php"); ?>
            </nav>
        </div>
        <a class="flex items-center justify-center rounded-md overflow-hidden p-1  w-12 h-12 hover:ring"
           href="perfil">
            <img src="<?= htmlspecialchars($_SESSION['img_perfil']) ?>" class="w-full h-full object-cover rounded-md"
                 alt="Imagem de perfil">
        </a>
    </div>
</header>
<!--menu mobile-->
<nav class="fixed flex gap-3 overflow-auto max-w-full p-3 border-t border-borda bg-cinza/80 backdrop-blur-lg z-700 bottom-0 left-0">
    <?php include("links_nav.php"); ?>
</nav>