<?php include("includes/inicio.php"); ?>
    <div class="container-form">
        <form action="backend/auth/login.php" method="POST">
            <h1 class="logo">Waly<span>Flix</span></h1>
            <h2 class="text-2xl font-bold mt-5">Entrar na sua conta</h2>
            <p class="text-white/70">Bem-vindo de volta ao WalyFlix</p>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="seu@email.com" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="Sua senha" required>
            </div>
            <button class="btn-enviar">Entrar</button>

            <div class="container-links">
                <p>Não tem uma conta? <a href="cadastro">Cadastre-se aqui</a></p>
                <a href="./"><i class="bi bi-arrow-left"></i> Voltar para a página inicial</a>
            </div>
        </form>
    </div>
<?php include("includes/final.php"); ?>