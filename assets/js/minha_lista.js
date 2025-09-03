// btn minha lista via fetch
document.getElementById("form-minha-lista").addEventListener("submit", async (e) => {
    e.preventDefault();

    let formData = new FormData(e.target);

    let response = await fetch("toggle_minha_lista", {
        method: "POST",
        body: formData
    });

    let resultado = await response.json();

    if (resultado.sucesso) {
        let btn = document.querySelector("#btn-minha-lista i");

        if (resultado.acao === "adicionado") {
            btn.classList.remove("bi-plus-lg");
            btn.classList.add("bi-check2");
        } else if (resultado.acao = "removido") {
            btn.classList.remove("bi-check2");
            btn.classList.add("bi-plus-lg");
        }
    } else {
        console.error(resultado.erro || "Erro desconhecido");
    }
});