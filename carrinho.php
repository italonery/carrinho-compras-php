<?php
if (isset($_POST['produto_id'], $_POST['quantidade']) && is_numeric($_POST['produto_id']) && is_numeric($_POST['quantidade'])) {
    $produto_id = (int)$_POST['produto_id'];
    $quantidade = (int)$_POST['quantidade'];
    $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
    $stmt->execute([$_POST['produto_id']]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto && $quantidade > 0) {
        if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
            if (array_key_exists($produto_id, $_SESSION['carrinho'])) {
                $_SESSION['carrinho'][$produto_id] += $quantidade;
            } else {
                $_SESSION['carrinho'][$produto_id] = $quantidade;
            }
        } else {
            $_SESSION['carrinho'] = array($produto_id => $quantidade);
        }
    }
    header('location: index.php?page=carrinho');
    exit;
}

if (isset($_GET['remover']) && is_numeric($_GET['remover']) && isset($_SESSION['carrinho']) && isset($_SESSION['carrinho'][$_GET['remover']])) {
    unset($_SESSION['carrinho'][$_GET['remover']]);
}

if (isset($_POST['atualizar']) && isset($_SESSION['carrinho'])) {
    foreach ($_POST as $k => $v) {
        if (strpos($k, 'quantidade') !== false && is_numeric($v)) {
            $id = str_replace('quantidade-', '', $k);
            $quantidade = (int)$v;
            if (is_numeric($id) && isset($_SESSION['carrinho'][$id]) && $quantidade > 0) {
                $_SESSION['carrinho'][$id] = $quantidade;
            }
        }
    }
    header('location: index.php?page=carrinho');
    exit;
}

if (isset($_POST['fazerpedido']) && isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
    header('Location: index.php?page=fazerpedido');
    exit;
}

$produtos_no_carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : array();
$produtos = array();
$subtotal = 0.00;
if ($produtos_no_carrinho) {
    $array_para_pontos_de_interrogacao = implode(',', array_fill(0, count($produtos_no_carrinho), '?'));
    $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id IN (' . $array_para_pontos_de_interrogacao . ')');
    $stmt->execute(array_keys($produtos_no_carrinho));
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Calculate the subtotal
    foreach ($produtos as $produto) {
        $subtotal += (float)$produto['preco'] * (int)$produtos_no_carrinho[$produto['id']];
    }
}
?>

<?=template_header('Carrinho')?>

<div class="carrinho content-wrapper">
    <h1>Carrinho de Compras</h1>
    <form action="index.php?page=carrinho" method="post">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Produto</td>
                    <td>Preço</td>
                    <td>Quantidade</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($produtos)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Seu carrinho de compras está vazio</td>
                </tr>
                <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td class="img">
                        <a href="index.php?page=produto&id=<?=$produto['id']?>">
                            <img src="imgs/<?=$produto['img']?>" width="50" height="50" alt="<?=$produto['nome']?>">
                        </a>
                    </td>
                    <td>
                        <a href="index.php?page=produto&id=<?=$produto['id']?>"><?=$produto['nome']?></a>
                        <br>
                        <a href="index.php?page=carrinho&remover=<?=$produto['id']?>" class="remover">Remover</a>
                    </td>
                    <td class="preco">R$<?=$produto['preco']?></td>
                    <td class="quantidade">
                        <input type="number" name="quantidade-<?=$produto['id']?>" value="<?=$produtos_no_carrinho[$produto['id']]?>" min="1" max="<?=$produto['quantidade']?>" placeholder="Quantidade" required>
                    </td>
                    <td class="preco">R$<?=$produto['preco'] * $produtos_no_carrinho[$produto['id']]?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="subtotal">
            <span class="texto">Subtotal</span>
            <span class="preco">R$<?=$subtotal?></span>
        </div>
        <div class="botoes">
            <input type="submit" value="Atualizar" name="atualiza">
            <input type="submit" value="Fazer Pedido" name="fazerpedido">
        </div>
    </form>
</div>

<?=template_footer()?>
