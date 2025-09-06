// btn minha lista via fetch
document.querySelectorAll("form.form-minha-lista").forEach(form => {
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        let formData = new FormData(e.target);

        let response = await fetch("toggle_minha_lista", {
            method: "POST",
            body: formData
        });

        let resultado = await response.json();
        let num_filmes = document.getElementById("num-filmes");

        if (resultado.sucesso) {
            let btn = form.querySelector("button i");

            if (resultado.acao === "adicionado") {
                btn.classList.remove("bi-plus-lg");
                btn.classList.add("bi-check2");
            } else if (resultado.acao === "removido") {
                btn.classList.add("bi-plus-lg");
                btn.classList.remove("bi-check2");

                // remover o card do filme e diminui a contagem de filmes
                form.closest("a").remove();
                num_filmes.textContent = parseInt(num_filmes.textContent, 10) - 1;
                console.log("Teste")
            }
        } else {
            console.error(resultado.erro || "Erro desconhecido");
        }
    });
});