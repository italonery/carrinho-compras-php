<?php
$stmt = $pdo->prepare('SELECT * FROM produtos ORDER BY data_adicionada DESC LIMIT 4');
$stmt->execute();
$produtos_recentemente_adicionados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Início')?>

<div class="destaque">
    <h2>Componentes</h2>
    <p>Hardware, o crème de la crème</p>
</div>
<div class="recentementeadicionado content-wrapper">
    <h2>Produtos Recentemente Adicionados</h2>
    <div class="produtos">
        <?php foreach ($produtos_recentemente_adicionados as $produto): ?>
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
</div>

<?=template_footer()?>
