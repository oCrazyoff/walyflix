<?php
$titulo = "Buscar";
include __DIR__ . "/../includes/inicio.php";
?>
<main>
    <div class="interface">
        <div class="flex flex-col pt-10 items-center justify-center w-full">
            <h2 class="text-3xl lg:text-4xl font-bold text-center">Buscar Filmes e Séries</h2>
            <p class="text-branco-texto-opaco text-lg lg:text-xl">Encontre seus conteúdos favoritos</p>
        </div>
        <div class="flex items-center justify-center my-5 w-full">
            <input class="w-full lg:w-2/3 border border-borda h-full p-3 text-xl rounded-lg ring-2 ring-azul" type="search"
                   name="buscar"
                   id="buscar"
                   placeholder="Buscar...">
        </div>
        <div id="placeholder-buscar" class="flex flex-col items-center jutifiy-center w-full">
            <i class="bi bi-search text-6xl lg:text-7xl text-branco-texto-opaco mt-0 lg:mt-10"></i>
            <h3 class="text-3xl my-5">Comece a pesquisar</h3>
            <p class="text-branco-texto-opaco text-xl text-center max-w-full lg:max-w-1/2">
                Digite o nome do filme, série ou categoria que você está procurando na barra de busca acima.
            </p>
        </div>
        <div class="flex items-center justify-between w-full text-xl">
            <h3 id="txt-resultados"></h3>
            <p id="qtd-resultados" class="text-branco-texto-opaco"></p>
        </div>
        <div id="resultados" class="flex flex-wrap gap-3 mt-5"></div>
    </div>
</main>
<script>
    document.getElementById("buscar").addEventListener("input", function () {
        let query = this.value;

        if (query.length > 2) { // só busca depois de 3 letras
            fetch("procurar_filmes?q=" + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    let container = document.getElementById("resultados");
                    let txt_resultado = document.getElementById("txt-resultados");
                    let qtd_resultado = document.getElementById("qtd-resultados");
                    let placeholder = document.getElementById("placeholder-buscar");
                    let resultados = 0;
                    container.innerHTML = "";


                    if (data.length > 0) {
                        placeholder.style.display = "none";
                        txt_resultado.innerHTML = `Resultados para "${query}"`;
                        data.forEach(filme => {
                            resultados++;
                            qtd_resultado.innerHTML = `${resultados} resultados`
                            let imagem = filme.imagem_url ? filme.imagem_url
                                : "https://www.protrusmoto.com/wp-content/uploads/revslider/home5/placeholder-1200x500.png";

                            container.innerHTML += `
                            <a class="p-1" href="info?filme=${filme.id}">
                                <img class="ml-1 p-1 w-[10rem] h-[15rem] object-cover rounded-lg hover:ring-2"
                                     src="${imagem}"
                                     alt="Capa do filme ${filme.titulo}">
                            </a>
                        `;
                        });
                    } else {
                        container.innerHTML = "<p>Nenhum resultado encontrado.</p>";
                    }
                });
        }
    });
</script>
<?php include __DIR__ . "/../includes/final.php"; ?>
