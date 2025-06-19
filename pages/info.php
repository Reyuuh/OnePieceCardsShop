<?php
require_once("components/navbar.php");
require_once("components/footer.php");
require_once("models/Database.php");
require_once("models/Product.php");

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    echo "Produkt-ID saknas.";
    exit;
}


$db = new Database();
$product = $db->getProduct($id);

if (!$product) {
    echo "Produkt hittades inte.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product->title) ?></title>
    <link rel="stylesheet" href="/styles/productInfo.css">
</head>
<body>

<?php navBar(); ?>
<a href="/" class="btn">Tillbaka</a>
<section class="product-info-page">
    
    <div class="-product-container">
        <div class="product-details">
        <img src="<?= htmlspecialchars($product->imageUrl ?? 'public/images/default.jpg') ?>" alt="product-img" class="specific-product">
            <h1><?= htmlspecialchars($product->title) ?></h1>
            <p><?= htmlspecialchars($product->description)?></p>
            <p><strong>Pris:</strong> <?= number_format($product->price, 2) ?> kr</p>
             <div class="addcart-container">
                                    <form method="GET" action="/addToCart">
                                        <input type="hidden" name="productId" value="<?php echo $product->id; ?>">
                                        <input type="hidden" name="fromPage" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
                                        <button type="submit" class="addtocart-button">Add to cart</button>
                                    </form>
                                </div>
          
            
        </div>
    </div>
</section>

<?php footer(); ?>

</body>
</html>
