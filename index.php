<?php 
require_once("Utils/router.php");
require_once("vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(".");
$dotenv->load();


$router = new Router();
        $router->addRoute('/', function () {
        require_once(__DIR__ .'/Pages/index.php');
        });


     
        $router->addRoute('/products', function () {
        require_once(__DIR__ .'/Pages/products.php');
        });


        $router->addRoute('/info', function() {
         require __DIR__ . '/Pages/info.php';
        });    


        $router->addRoute('/search', function() {
                require __DIR__ . '/Pages/search.php';
               });            

        $router->addRoute('/user/login', function () {
        require_once( __DIR__ .'/Pages/users/login.php');
        });

        $router->addRoute('/user/logout', function () {
                require_once( __DIR__ .'/Pages/users/logout.php');
            });

            $router->addRoute('/user/register', function () {
                require_once( __DIR__ .'/Pages/users/register.php');
            });

            $router->addRoute('/user/registerDone', function () {
                require_once( __DIR__ .'/Pages/users/registerDone.php');
            });

           $router->addRoute('/addToCart', function () {
           require_once( __DIR__ .'/Pages/addToCart.php');
           });

               $router->addRoute('/checkout', function () { 
           require_once( __DIR__ .'/Pages/checkout.php');
           });

            $router->addRoute('/checkoutsuccess', function () {
            require_once( __DIR__ .'/Pages/checkoutsuccess.php');
           });

            
$router->dispatch();
?>