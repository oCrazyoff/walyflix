<?php
$titulo = "Gerenciar Usuários";
include __DIR__ . "/../includes/inicio.php";

// puxando todos os usuários
$sql = "SELECT id, nome, email, cargo FROM usuarios";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
?>
<main>
    <div class="interface">
        <div class="titulo">
            <div class="txt-titulo">
                <h2><i class="bi bi-people"></i> Gerenciar Usuários</h2>
                <p>Visualize e gerencie todos os usuários da plataforma.</p>
            </div>
            <button onclick="abrirCadastrarModal('usuarios')"><i class="bi bi-plus"></i> <span>Novo Usuário</span>
            </button>
        </div>
        <div class="container-table-titulo">
            <h3>Lista de Usuários</h3>
            <p>
                <?= $resultado->num_rows ?> usuários encontrados
            </p>
            <div class="container-table">
                <?php if ($resultado->num_rows > 0): ?>
                    <table>
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Cargo</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nome']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td>
                                    <?php
                                    // trocando o estilo caso for comum ou adm
                                    if ($row['cargo'] == 0):
                                        ?>
                                        <p class="self-center justify-self-center w-max text-white font-bold px-5 rounded-full border border-borda bg-cinza">
                                            Comum</p>
                                    <?php else: ?>
                                        <p class="self-center justify-self-center w-max text-white font-bold px-5 rounded-full bg-purple-700">
                                            Admin</p>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="container-acoes">
                                        <button onclick="abrirEditarModal('usuarios', <?= htmlspecialchars($row['id']) ?>)">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form class="deletar"
                                              action="deletar_usuarios?id=<?= htmlspecialchars($row['id']) ?>"
                                              method="POST">
                                            <input type="hidden" name="csrf" id="csrf"
                                                   value="<?= htmlspecialchars(gerarCSRF()) ?>">
                                            <button type="submit"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else:
                    $_SESSION['resposta'] = "Nenhum usuário encontrado!" ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php
$tipoModal = 'usuarios';
include __DIR__ . "/../includes/modal.php";
?>
<?php include __DIR__ . "/../includes/final.php"; ?>
