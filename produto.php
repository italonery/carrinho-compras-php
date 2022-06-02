<?php
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        exit('Produto inexistente!');
    }
} else {
    exit('Produto inexistente!');
}
?>

<?=template_header('Produto')?>

<div class="produto content-wrapper">
    <img src="imgs/<?=$produto['img']?>" width="500" height="500" alt="<?=$produto['nome']?>">
    <div>
        <h1 class="nome"><?=$produto['nome']?></h1>
        <span class="preco">
            &R$;<?=$produto['preco']?>
            <?php if ($produto['pdv'] > 0): ?>
            <span class="pdv">&R$;<?=$produto['pdv']?></span>
            <?php endif; ?>
        </span>
        <form action="index.php?page=carrinho" method="post">
            <input type="number" name="quantidade" value="1" min="1" max="<?=$produto['quantidade']?>" placeholder="quantidade" required>
            <input type="hidden" name="produto_id" value="<?=$produto['id']?>">
            <input type="submit" value="Adicionar ao carrinho">
        </form>
        <div class="descricao">
            <?=$produto['descricao']?>
        </div>
    </div>
</div>

<?=template_footer()?>
