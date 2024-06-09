<?php

use backend\service\ProductService;

require_once "./service/ProductService.php";
require_once "./tools/ProductTools.php";

try {
    $obProduct = new ProductService($_GET['id'] ?? 0);
} catch (Exception $e) {
    echo 'Произошла ошибка: ' . $e->getMessage();
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles/style.css">
    <title>Товары</title>
</head>
<body>
<section class="main-page">
    <aside class="main-page__aside">
        <a href="?id=0">Все товары</a>
        <?= \backend\tools\ProductTools::getHtmlGroups($obProduct->getGroups()) ?>
    </aside>
    <main>
        <?= \backend\tools\ProductTools::getHtmlProducts($obProduct->getProducts()) ?>
    </main>
</section>
</body>
</html>