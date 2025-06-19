<?php
require_once("models/Database.php");
require_once("models/Product.php");
require_once("components/navbar.php");
require_once("components/footer.php");

$dbContext = new Database();

$categoryName = $_GET['name'] ?? '';
$products = $dbContext->getCategoryProducts($categoryName);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($categoryName) ?> - Category</title>
    <link rel="stylesheet" href="../styles/products.css">
</head>
<body>
    <?php navBar(); ?>
    

    <h1 class="product-title"><?= htmlspecialchars($categoryName) ?></h1>

    <div class="product-grid">
        <?php if (empty($products)): ?>
            <p>No products found in this category.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                <img src="<?= htmlspecialchars($product->imageUrl ?? 'public/images/default.jpg') ?>" alt="product-img">
<div class="info container" >
                    <h2><?= htmlspecialchars($product->title) ?></h2>
                    <p>Price: <?= number_format($product->price, 2) ?> kr</p>
                    <a href="info?id=<?= $product->id ?>" class="btn-visaprodukt">Visa produkt</a>
</div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php footer(); ?>
</body>
</html>