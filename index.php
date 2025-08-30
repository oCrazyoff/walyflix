<?php include("includes/inicio.php"); ?>
    <header class="sticky top-0 left-0 h-[5rem]">
        <div class="interface flex justify-between w-full">
            <h1 class="logo">Waly<span>Flix</span></h1>
            <div class="flex gap-5">
                <a href="login" class="py-2 px-4 rounded-lg hover:bg-cinza-claro">Entrar</a>
                <a href="cadastro" class="bg-azul py-2 px-4 rounded-lg hover:bg-azul-hover">Cadastrar</a>
            </div>
        </div>
    </header>
    <section class="flex items-center justify-center h-[calc(100dvh-5rem)]">
        <div class="interface flex flex-col gap-5 items-center justify-center">
            <h2 class="text-6xl font-bold">Filmes e séries ilimitados</h2>
            <p class="text-3xl text-white/70">
                Assista onde quiser. Cancele quando quiser.
            </p>
            <p class="text-2xl text-white/70">
                Pronto para assistir? Informe seu email para criar ou reiniciar sua assinatura.
            </p>
            <a href="cadastro"
               class="flex items-center justify-center gap-2 rounded-lg p-4 text-2xl w-1/2 bg-azul hover:bg-azul-hover"><i
                        class="bi bi-play"></i> Começar Agora</a>
        </div>
    </section>
<?php include("includes/final.php"); ?>