<?php
$titulo = "Cadastro";
$n_valida = true;
include("includes/inicio.php");
?>
    <div class="container-form">
        <form action="fazer_cadastro" method="POST">
            <h1 class="logo">Waly<span>Flix</span></h1>
            <h2 class="text-2xl font-bold mt-5">Criar conta</h2>
            <p class="text-white/70">Junte-se ao WalyFlix gratuitamente</p>

            <!--inputs escondidos-->
            <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">

            <div class="input-group">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" placeholder="Seu nome" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="seu@email.com" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="Sua senha" required>
            </div>
            <div class="input-group">
                <label for="confirmar-senha">Confirmar Senha</label>
                <input type="password" name="confirmar-senha" id="confirmar-senha" placeholder="Confirme sua senha"
                       required>
            </div>
            <button class="btn-enviar">Criar Conta</button>

            <div class="container-links">
                <p>Já tem uma conta? <a href="login">Faça login aqui</a></p>
                <a href="./"><i class="bi bi-arrow-left"></i> Voltar para a página inicial</a>
            </div>
        </form>
    </div>
<?php include("includes/final.php"); ?>