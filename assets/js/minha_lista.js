// Seleciona todos os formulários possíveis (de detalhes ou da lista)
document.querySelectorAll("form.form-minha-lista, form.form-minha-lista-info").forEach(form => {
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
                // Troca o ícone caso seja botão de adicionar/remover
                if (btn) {
                    btn.classList.add("bi-plus-lg");
                    btn.classList.remove("bi-check2");
                }

                // Se estiver na lista de filmes -> remove o card inteiro
                form.closest("a")?.remove();

                // Atualiza a contagem com valor do backend
                if (resultado.hasOwnProperty("nova_contagem")) {
                    num_filmes.textContent = resultado.nova_contagem;

                    // Se não restar nenhum filme, recarrega a página para exibir msg "lista vazia"
                    if (resultado.nova_contagem === 0) {
                        location.reload();
                    }
                }
            }
        } else {
            console.error(resultado.erro || "Erro desconhecido");
        }
    });
});