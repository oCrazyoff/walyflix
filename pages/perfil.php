<?php
$titulo = "Editar Perfil";
include __DIR__ . "/../includes/inicio.php";

// puxando value da imagem de perfil
$sql = "SELECT img_perfil FROM usuarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();
$stmt->bind_result($img_perfil_id);
$stmt->fetch();
$stmt->close();
?>
<main>
    <div class="interface">
        <div class="titulo">
            <div class="txt-titulo">
                <h2><i class="bi bi-person"></i> Editar Perfil</h2>
                <p>Confira e edite a suas informações</p>
            </div>
        </div>
        <div class="interface-perfil">
            <form id="form-perfil"
                  class="flex flex-col justify-center items-center gap-8 w-full lg:max-w-1/2"
                  action="atualizar_perfil"
                  method="POST">
                <div class="flex flex-col lg:flex-row items-center justify-center lg:justify-between gap-2 w-full">
                    <button onclick="mostrarModalFoto()" type="button"
                            class="relative flex items-center justify-center w-50 h-50 rounded-lg overflow-hidden cursor-pointer p-1 hover:ring">
                        <img id="foto-perfil"
                             src="<?= htmlspecialchars($_SESSION['img_perfil']) ?>"
                             class="w-full h-full object-cover rounded-lg"
                             alt="Imagem do perfil">
                        <span class="absolute flex items-center justify-center text-3xl top-1/2 left-1/2 -translate-1/2 w-15 h-15 rounded-full bg-cinza/40 text-white/90">
                            <i class="bi bi-pencil"></i>
                        </span>
                    </button>
                    <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                    <input type="hidden" name="img-perfil" id="img-perfil"
                           value="<?= htmlspecialchars($img_perfil_id) ?>">
                    <div class="flex flex-col gap-3 min-h-full w-full lg:w-3/5">
                        <div class="input-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" id="nome"
                                   placeholder="Seu nome" value="<?= htmlspecialchars($_SESSION['nome']) ?>">
                        </div>
                        <div class="input-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email"
                                   placeholder="Seu email" value="<?= htmlspecialchars($_SESSION['email']) ?>">
                        </div>
                    </div>
                </div>
                <div class="w-full border-t-3 border-borda pt-8">
                    <h3 class="text-3xl font-bold">Troca de senha</h3>
                    <p class="text-xl text-branco-texto-opaco mb-5">Por favor, digite a sua senha atual e, em seguida,
                        escolha
                        uma nova senha para que a troca seja realizada com sucesso!</p>
                    <input type="password" name="senha" id="senha"
                           class="input-senha"
                           placeholder="Sua senha atual">
                    <input type="password" name="nova-senha" id="nova-senha"
                           class="input-senha"
                           placeholder="Digite sua nova senha">
                </div>
                <button type="submit"
                        class="rounded-lg p-2 text-2xl w-full bg-azul hover:bg-azul-hover cursor-pointer">
                    Salvar
                </button>
                <a href="deslogar" class="rounded-lg p-2 text-2xl text-center w-full bg-red-500 hover:bg-red-700">
                    <i class="bi bi-box-arrow-in-left"></i> Deslogar
                </a>
            </form>
        </div>
    </div>
</main>
<!--modal das fotos de perfil-->
<div class="flex items-center hidden justify-center fixed top-0 left-0 w-full h-full bg-black/60" id="modal-perfil">
    <div class="flex w-[90%] lg:w-max gap-3 bg-cinza p-5 rounded-lg border border-borda">
        <button onclick="mudarFoto(0, '<?= BASE_URL . "assets/img/perfil/macaco.webp" ?>')">
            <img src="<?= BASE_URL . "assets/img/perfil/macaco.webp" ?>"
                 class="p-1 w-35 h-35 object-cover rounded-lg hover:ring cursor-pointer"
                 alt="Imagem do perfil">
        </button>
        <button onclick="mudarFoto(1, '<?= BASE_URL . "assets/img/perfil/macaca.webp" ?>')">
            <img src="<?= BASE_URL . "assets/img/perfil/macaca.webp" ?>"
                 class="p-1 w-35 h-35 object-cover rounded-lg hover:ring cursor-pointer"
                 alt="Imagem do perfil">
        </button>
        <button onclick="mudarFoto(2, '<?= BASE_URL . "assets/img/perfil/macagay.webp" ?>')">
            <img src="<?= BASE_URL . "assets/img/perfil/macagay.webp" ?>"
                 class="p-1 w-35 h-35 object-cover rounded-lg hover:ring cursor-pointer"
                 alt="Imagem do perfil">
        </button>
    </div>
</div>
<script>
    // modal das fotos de perfil
    const input_img = document.getElementById("img-perfil");
    const modal = document.getElementById('modal-perfil');
    const foto_perfil = document.getElementById('foto-perfil');

    function mostrarModalFoto() {
        modal.style.display = 'flex';
    }

    function mudarFoto(foto_id, src) {
        input_img.value = foto_id;
        foto_perfil.src = src;
        modal.style.display = 'none';
    }
</script>
<?php include __DIR__ . "/../includes/final.php"; ?>
