<?php
$n_valida = true;
include("includes/inicio.php");
?>
    <header class="sticky top-0 left-0 h-[5rem] py-5 bg-preto/50 backdrop-blur-xl">
        <div class="interface flex justify-between w-full">
            <h1 class="logo">Waly<span>Flix</span></h1>
            <div class="flex gap-5">
                <a href="login" class="py-2 px-4 rounded-lg hover:bg-cinza-claro">Entrar</a>
                <a href="cadastro"
                   class="hidden lg:block bg-azul py-2 px-4 rounded-lg hover:bg-azul-hover">Cadastrar</a>
            </div>
        </div>
    </header>
    <section
            class="flex items-center justify-center text-center h-auto lg:h-[calc(100dvh-5rem)]">
        <div class="interface flex flex-col gap-3 lg:gap-5 items-center justify-center">
            <h2 class="text-3xl lg:text-6xl font-bold">Filmes e séries ilimitados</h2>
            <p class="text-lg lg:text-3xl text-white/70">
                Assista onde quiser. Cancele quando quiser.
            </p>
            <p class="text-center text-base lg:text-2xl text-white/70">
                Pronto para assistir? Informe seu email para criar ou reiniciar sua assinatura.
            </p>
            <a href="cadastro"
               class="flex items-center justify-center gap-2 rounded-lg p-2 lg:p-3 lg:p-4 text-xl lg:text-2xl w-full lg:w-1/2
               bg-azul hover:bg-azul-hover">
                <i class="bi bi-play"></i> Começar Agora
            </a>
            <img class="h-75 lg:h-80 rounded-lg" src="<?= BASE_URL . "assets/img/capa_landing.png" ?>"
                 alt="Capas variadas de filmes">
        </div>
    </section>
    <section>
        <div class="interface">
            <div class="container-vantagens">
                <article>
                    <i class="bi bi-play"></i>
                    <h2>Assista em qualquer lugar</h2>
                    <p>Transmita filmes ilimitados no seu telefone, tablet e laptop.</p>
                </article>
                <article>
                    <i class="bi bi-bookmark"></i>
                    <h2>Crie sua lista</h2>
                    <p>Salve seus filmes favoritos para assistir depois. Conteúdo premium</p>
                </article>
                <article>
                    <i class="bi bi-star"></i>
                    <h2>Conteúdo premium</h2>
                    <p>Acesse milhares de filmes selecionados em alta qualidade.</p>
                </article>
            </div>
        </div>
    </section>
    <section class="py-10 border-y border-borda bg-cinza text-center">
        <div class="interface flex flex-col gap-5 items-center justify-center">
            <h2 class="text-3xl lg:text-4xl font-bold">Pronto para começar?</h2>
            <p class="text-branco-texto-opaco text-lg lg:text-xl text-center">Junte-se a milhões de pessoas que já
                descobriram uma
                nova forma de assistir filmes e séries.</p>
            <div class="flex items-center justify-center gap-5">
                <a class="rounded-lg px-5 lg:px-10 py-3 text-xl bg-azul hover:bg-azul-hover" href="cadastro">Criar
                    Conta</a>
                <a class="rounded-lg px-5 lg:px-10 py-3 text-xl border border-borda hover:bg-cinza-claro" href="login">Já
                    tenho
                    conta</a>
            </div>
        </div>
    </section>
    <footer class="bg-black py-5 text-center">
        <div class="interface flex flex-col gap-3 items-center justify-center">
            <p>© <span id="ano-atual"></span> <a class="border-b hover:text-azul" href="https://www.walysson.com.br/">Walysson</a>.
                Todos os direitos
                reservados.</p>
        </div>
    </footer>
    <script>
        // pegando o ano atual
        document.getElementById('ano-atual').textContent = new Date().getFullYear();
    </script>
<?php include("includes/final.php"); ?>