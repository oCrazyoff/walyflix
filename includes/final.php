</body>
<?php
if (isset($_SESSION['resposta'])): ?>
    <!--div de erro-->
    <div class="div-erro">
        <?= htmlspecialchars($_SESSION['resposta']) ?>
    </div>
    <?php
    unset($_SESSION['resposta']);
endif;
?>
</html>