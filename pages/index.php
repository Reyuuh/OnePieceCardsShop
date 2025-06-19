<?php 
require_once("components/navbar.php");
require_once("components/footer.php");
require_once("models/Database.php");
require_once("models/Product.php"); 
require_once("components/SingleProduct.php"); 

$dbContext = new Database(); // Anslut till databasen
$popularProducts = $dbContext->getPopularProducts(); // Hämta populära produkter
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Homepage</title>
    <link rel="stylesheet" href="../styles/landingPage.css">
</head>
<body>
    <?php navBar(); ?>

    <div class="promo-art">
        
        <img src="public/images/hithere.png" alt="image">
    </div>

    <section class="products">
        <div class="container">
            <div class="product-grid">
                <?php foreach($popularProducts as $products): ?>
                    <div class="product-card">
                        <?php if($products->price < 10): ?>
                            <div class="badge">Sale</div>
                        <?php endif; ?>
                        <img src="<?= htmlspecialchars($products->imageUrl ?? 'public/images/default.jpg') ?>" alt="product-img" class="product-image">

                        <div class="product-container">
                        <div class="product-info">
                        
                            <h5 class="product-title"><?= htmlspecialchars($products->title) ?></h5>  
                            <p class="product-price"><?= number_format($products->price, 2) ?> kr</p>
                        </div>
                        <div class="product-action">
                            <a  href="info?id=<?= $products->id ?>" class="btn-product">Show product</a>
                        </div>
                              <div class="addcart-container">
                                    <form class="form-addtocart" method="GET" action="/addToCart">
                                        <input type="hidden" name="productId" value="<?php echo $products->id; ?>">
                                        <input type="hidden" name="fromPage" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
                                        <button type="submit" class="addtocart-button">Add to cart</button>
                                    </form>
                                </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    
    <?php footer(); ?>

    <script src="/scripts/cart.js"></script>
</body>
</html>
