<?php 
require_once("utils/router.php");
require_once("vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(".");
$dotenv->load();


$router = new Router();
        $router->addRoute('/', function () {
        require_once(__DIR__ .'/pages/index.php');
        });


     
        $router->addRoute('/products', function () {
        require_once(__DIR__ .'/pages/products.php');
        });


        $router->addRoute('/info', function() {
         require __DIR__ . '/pages/info.php';
        });    


        $router->addRoute('/search', function() {
                require __DIR__ . '/pages/search.php';
               });            

        $router->addRoute('/user/login', function () {
        require_once( __DIR__ .'/pages/users/login.php');
        });

        $router->addRoute('/user/logout', function () {
                require_once( __DIR__ .'/pages/users/logout.php');
            });

            $router->addRoute('/user/register', function () {
                require_once( __DIR__ .'/pages/users/register.php');
            });

            $router->addRoute('/user/registerDone', function () {
                require_once( __DIR__ .'/pages/users/registerDone.php');
            });

           $router->addRoute('/addToCart', function () {
           require_once( __DIR__ .'/pages/addToCart.php');
           });

               $router->addRoute('/checkout', function () { 
           require_once( __DIR__ .'/pages/checkout.php');
           });

            $router->addRoute('/checkoutsuccess', function () {
            require_once( __DIR__ .'/pages/checkoutSuccess.php');
           });

            
$router->dispatch();
?>