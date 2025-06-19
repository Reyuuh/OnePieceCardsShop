<?php
require_once("models/Database.php");
require_once("models/Cart.php");

function navBar() {
    $db = new Database();

    $userId = null;
$session_id = null;

if($db->getUsersDatabase()->getAuth()->isLoggedIn()){
    $userId = $db->getUsersDatabase()->getAuth()->getUserId();
}
    //$cart = $dbContext->getCartByUser($userId);
$session_id = session_id();

$cart = new Cart($db, $session_id, $userId);


    $categories = $db->getAllCategories();

    
?>
    <link rel="stylesheet" href="../styles/navbar.css">
    
    <nav class="navbar">
        <ul class="nav-links">
            <div class="logo">
            
            <?php echo '<a class="logo-img" href="/"><img src="/public/images/nav-logo.png" alt="Logo" /></a>'; ?>

            </div>

            <li class="nav-link"><a href="/">HOME</a></li>

            <li class="nav-link products">
                <a href="#">CATEGORIES</a>
                <ul class="drop-down">
                    <?php foreach ($categories as $category): ?>
                        <li><a href="/products?name=<?= urlencode($category) ?>"><?= htmlspecialchars($category) ?></a></li>
                    <?php endforeach; ?>
                    
                </ul>
            </li>

            <?php
                        if($db->getUsersDatabase()->getAuth()->isLoggedIn()){ ?>
                            <li class="nav-item"><a class="nav-link" href="/user/logout">LOGOUT</a></li>
                        <?php }else{ ?>
                            <li class="nav-item"><a class="nav-link" href="/user/login">LOGIN</a></li>
                            <li class="nav-item"><a class="nav-link" href="/user/register">CREATE ACCOUNT</a></li>
                        <?php 
                        }
                        ?>
                        <?php if($db->getUsersDatabase()->getAuth()->isLoggedIn()){ ?>
                        Current user: <?php echo $db->getUsersDatabase()->getAuth()->getUsername() ?>
                    <?php } ?>
                        <div class="search-container" >
            <form action="/search" method="GET" class="search-form">
    <input type="text" name="q" placeholder="search" class="form-control" />
    <button type="submit" class="search-button">Search</button>
</form>

<form class="">
                        <a class="" href="/addToCart">
                            <i class=""></i>
                            Cart
                            <span id="cartCount" class="">
                                <a href="/checkout" onclick="onCheckout()" class="btn btn-success">Checkout</a>
                                <?php echo $cart->getItemsCount() ?></span>
                        </a>
                    </form>

     </div>       
    </ul>
   </nav>
<?php
}
?>