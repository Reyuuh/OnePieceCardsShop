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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($categoryName) ?> - Category</title>
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/variables.css">
    <link rel="stylesheet" href="/styles/global.css">
    <link rel="stylesheet" href="/styles/products.css">
</head>
<body>
    <?php navBar(); ?>

    <section class="product-section">
        <div class="container">
            <h1 class="category-title"><?= htmlspecialchars($categoryName) ?></h1>

            <div class="product-grid">
                <?php if (empty($products)): ?>
                    <p>No products found in this category.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <img src="<?= htmlspecialchars($product->imageUrl ?? 'public/images/default.jpg') ?>" alt="<?= htmlspecialchars($product->title) ?>" class="product-image">

                            <div class="product-container">
                                <div class="product-info">
                                    <h5 class="product-title"><?= htmlspecialchars($product->title) ?></h5>
                                    <p class="product-price"><?= number_format($product->price, 2) ?> kr</p>
                                </div>
                                <div class="product-action">
                                    <a href="/info?id=<?= $product->id ?>" class="btn-product">Show product</a>
                                </div>
                                <div class="add-cart-container">
                                    <form class="form-addtocart" method="GET" action="/addToCart">
                                        <input type="hidden" name="productId" value="<?= $product->id ?>">
                                        <input type="hidden" name="fromPage" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                                        <button type="submit" class="add-to-cart-btn">Add to cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php footer(); ?>

    <script src="/scripts/cart.js"></script>
</body>
</html>
