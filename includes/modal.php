<!--modal-->
<div id="modal" class="hidden fixed inset-0 flex items-center justify-center bg-black/80">
    <div class="bg-cinza border border-borda p-5 rounded-lg w-xl">
        <h2 id="modal-title" class="text-xl font-bold mb-4"></h2>
        <form id="modal-form" action="#" method="POST">
            <!--CSRF-->
            <input type="hidden" name="csrf" id="csrf" value="<?= htmlspecialchars(gerarCSRF()) ?>">
            <?php
            if (isset($tipoModal)):
                if ($tipoModal == 'filmes'): ?>
                    <!--filmes-->
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" class="input-modal" placeholder="Titulo do filme"
                           required>
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" id="descricao" class="input-modal" placeholder="Sinopse breve do filme"
                              required></textarea>
                    <label for="ano">Ano</label>
                    <input type="number" name="ano" id="ano" class="input-modal" placeholder="Ex: 2025" required>
                    <label for="categoria_id">Categoria</label>
                    <select name="categoria_id" id="categoria_id" class="input-modal" required>
                        <?php
                        // puxando todas categorias
                        $stmt = $conexao->prepare("SELECT id, nome FROM categorias");
                        $stmt->execute();
                        $resultado = $stmt->get_result();
                        $stmt->close();

                        if ($resultado->num_rows > 0): ?>
                            <option value="0" disabled selected>Escolha uma categoria!</option>
                            <?php while ($row = $resultado->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['nome']) ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option selected disabled>Nenhuma categória encontrada!</option>
                        <?php endif; ?>
                    </select>
                    <label for="imagem_url">URL da Imagem</label>
                    <input type="text" name="imagem_url" id="imagem_url" class="input-modal"
                           placeholder="Endereço da capa do filme"
                           required>
                    <label for="link_filme">Link do Filme</label>
                    <input type="text" name="link_filme" id="link_filme" class="input-modal"
                           placeholder="Link do filme (DROPBOX)"
                           required>

                <?php elseif
                ($tipoModal == 'categorias'): ?>
                    <!--categorias-->
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" class="input-modal" placeholder="Nome da categoria"
                           required>
                <?php elseif ($tipoModal == 'usuarios'): ?>
                    <!--usuarios-->
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" class="input-modal" placeholder="Nome do usuário"
                           required>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="input-modal" placeholder="Email do usuário"
                           required>
                    <label for="cargo">Cargo</label>
                    <select name="cargo" id="cargo" class="input-modal">
                        <option value="0">Comum</option>
                        <option value="1">Admin</option>
                    </select>
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" class="input-modal" placeholder="Senha do usuário">
                <?php endif; ?>
            <?php endif; ?>
            <div class="grid grid-cols-3 gap-2 mt-5">
                <button type="submit"
                        class="col-span-2 bg-azul text-white px-4 py-2 cursor-pointer rounded-lg hover:bg-azul-hover">
                    Enviar
                </button>
                <button type="button"
                        class="text-white px-4 py-2 border border-borda cursor-pointer rounded-lg hover:bg-cinza-claro"
                        onclick="fecharModal()">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // funções do modal

    // Capitaliza a primeira letra
    function capitalizarPrimeiraLetra(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function abrirCadastrarModal(tabela) {
        const modal = document.getElementById('modal');
        const form = document.getElementById('modal-form');

        modal.classList.remove('hidden');
        document.getElementById('modal-title').textContent = `Cadastrar ${capitalizarPrimeiraLetra(tabela)}`;

        // limpa campos do form
        form.reset();

        // altera action do form para o PHP de cadastro
        form.action = `cadastrar_${tabela}`;
    }

    async function abrirEditarModal(tabela, id) {
        const modal = document.getElementById('modal');
        const form = document.getElementById('modal-form');
        const modalTitle = document.getElementById('modal-title');

        // Mostrar modal imediatamente
        modal.classList.remove('hidden');

        // Coloca título temporário
        modalTitle.textContent = "Carregando...";

        // Altera action do form
        form.action = `editar_${tabela}?id=${id}`;

        try {
            // Busca os dados
            const resp = await fetch(`buscar_${tabela}?id=${id}`);
            const dados = await resp.json();

            // Preenche os campos do form
            for (const campo in dados) {
                if (form[campo]) form[campo].value = dados[campo];
            }

            // Atualiza título com o correto
            modalTitle.textContent = `Editar ${capitalizarPrimeiraLetra(tabela)}`;
        } catch (erro) {
            modalTitle.textContent = `Erro ao carregar ${capitalizarPrimeiraLetra(tabela)}`;
            console.error("Erro ao buscar dados:", erro);
        }
    }

    function fecharModal(tabela) {
        document.getElementById(`modal`).classList.add('hidden');
    }
</script>