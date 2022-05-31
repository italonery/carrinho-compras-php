<?php
function pdo_connect_mysql() {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'dbcarrinhocompras';
    try {
    	return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	exit('Failed to connect to database!');
    }
}
function template_header($title) {
$num_itens_no_carrinho = isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0;
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
        <header>
            <div class="content-wrapper">
                <h1>Carrinho de Compras</h1>
                <nav>
                    <a href="index.php">Início</a>
                    <a href="index.php?page=produtos">Produtos</a>
                </nav>
                <div class="link-icones">
                    <a href="index.php?page=carrinho">
						<i class="fas fa-shopping-cart"></i>
                        <span>$num_itens_no_carrinho</span>
					</a>
                </div>
            </div>
        </header>
        <main>
EOT;
}
function template_footer() {
$year = date('Y');
echo <<<EOT
        </main>
        <footer>
            <div class="content-wrapper">
                <p>&copy; $year, Carrinho de Compras</p>
            </div>
        </footer>
        <script src="script.js"></script>
    </body>
</html>
EOT;
}
?>