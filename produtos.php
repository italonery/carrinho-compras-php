<?php
$num_produtos_em_cada_pagina = 4;
$pagina_atual = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
$stmt = $pdo->prepare('SELECT * FROM produtos ORDER BY data_adicionada DESC LIMIT ?,?');
$stmt->bindValue(1, ($pagina_atual - 1) * $num_produtos_em_cada_pagina, PDO::PARAM_INT);
$stmt->bindValue(2, $num_produtos_em_cada_pagina, PDO::PARAM_INT);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_produtos = $pdo->query('SELECT * FROM produtos')->rowCount();
?>

<?=template_header('Produtos')?>

<div class="produtos content-wrapper">
    <h1>Produtos</h1>
    <p><?=$total_produtos?> Produtos</p>
    <div class="produtos-wrapper">
        <?php foreach ($produtos as $produto): ?>
        <a href="index.php?page=produto&id=<?=$produto['id']?>" class="produto">
            <img src="imgs/<?=$produto['img']?>" width="200" height="200" alt="<?=$produto['nome']?>">
            <span class="nome"><?=$produto['nome']?></span>
            <span class="preco">
                R$<?=$produto['preco']?>
                <?php if ($produto['pdv'] > 0): ?>
                <span class="pdv">R$<?=$produto['pdv']?></span>
                <?php endif; ?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="butoes">
        <?php if ($pagina_atual > 1): ?>
        <a href="index.php?page=produtos&p=<?=$pagina_atual-1?>">Prev</a>
        <?php endif; ?>
        <?php if ($total_produtos > ($pagina_atual * $num_produtos_em_cada_pagina) - $num_produtos_em_cada_pagina + count($produtos)): ?>
        <a href="index.php?page=produtos&p=<?=$pagina_atual+1?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>