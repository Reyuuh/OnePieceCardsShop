<?php 
function SingleProduct($prod) { ?>
<div class="">
<div class="">
    <?php if($prod->price < 10) {  ?>
        <div class="" style="">Sale</div>
    <?php } ?>        
    <!-- Product image-->
    <img class="" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
    <!-- Product details-->
    <div class="">
        <div class="text-center">
            <!-- Product name-->
             <a href="/product?id=<?php echo $prod->id; ?>">
            <h5 class="">
                <?php echo $prod->title; ?></h5>
            </a>
            <!-- Product price-->
            $<?php echo $prod->price; ?>.00
        </div>
    </div>
    <!-- Product actions-->
    <div class="">
        <div class="">
            <a class="" 
            href="javascript:addToCart(<?php echo $prod->id; ?>)">Add to cart</a></div>
    </div>
</div>
</div>    

<?php }
?>