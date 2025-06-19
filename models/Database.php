<?php 
require_once('UserDatabase.php');
require_once('vendor/autoload.php');
require_once('models/CartItem.php');

class Database {
    public $pdo;
    private $usersDatabase;
    
    function getUsersDatabase() {
        return $this->usersDatabase;
    }
    
    function __construct() {    
        $host = $_ENV['HOST'];
        $db   = $_ENV['DB'];
        $user = $_ENV['USER'];
        $pass = $_ENV['PASSWORD'];
        $port = $_ENV['PORT'];

        $dsn = "mysql:host=$host:$port;dbname=$db";
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->initDatabase();
        $this->modifyDatabase();

        $this->addProductIfNotExists(
            "One Piece TCG Playmat - Luffy", 
            199, 
            10, 
            "Accessories", 
            70,
            "public/images/luffyplaymat.jpg"
        );

    

        $this->usersDatabase = new UserDatabase($this->pdo);
        $this->usersDatabase->setupUsers();
        $this->usersDatabase->seedUsers();
    }
    
    // i Database.php
function getPdo() {
    return $this->pdo;
}

    function getCategoryIdOrCreate($name) {
        $stmt = $this->pdo->prepare("SELECT id FROM Categories WHERE name = :name");
        $stmt->execute(['name' => $name]);
        $id = $stmt->fetchColumn();
        if (!$id) {
            $stmt = $this->pdo->prepare("INSERT INTO Categories (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);
            $id = $this->pdo->lastInsertId();
        }
        return $id;
    }

    function addProductIfNotExists($title, $price, $stockLevel, $categoryName, $popularityFactor, $imageUrl = null) {
        $query = $this->pdo->prepare("SELECT * FROM Products WHERE title = :title");
        $query->execute(['title' => $title]);
        if ($query->rowCount() == 0) {
            $this->insertProduct($title, $stockLevel, $price, $categoryName, $popularityFactor, $imageUrl);
        }
    }
    

    function columnExists($pdo, $table, $column) {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM $table WHERE  field = :column");
        $stmt->execute(['column' => $column]);
        return $stmt->rowCount() > 0;
    }

    function modifyDatabase() {
        if (!$this->columnExists($this->pdo, 'Products', 'popularityFactor')) {
            $this->pdo->query('ALTER TABLE Products ADD COLUMN popularityFactor INT DEFAULT 0');
        }
        if (!$this->columnExists($this->pdo, 'Products', 'price')) {
            $this->pdo->query('ALTER TABLE Products ADD COLUMN price INT DEFAULT 0');
        }
        if (!$this->columnExists($this->pdo, 'Products', 'stockLevel')) {
            $this->pdo->query('ALTER TABLE Products ADD COLUMN stockLevel INT DEFAULT 0');
        }
        if (!$this->columnExists($this->pdo, 'Products', 'imageUrl')) {
            $this->pdo->query('ALTER TABLE Products ADD COLUMN imageUrl VARCHAR(255)');
        }
        
    }

    function searchProducts($q,$sortCol, $sortOrder){ // $q = oo
        if(!in_array($sortCol,[ "title","price"])){ // title123312132312321
            $sortCol = "title";
        }
        if(!in_array($sortOrder,["asc", "desc"])){
            $sortOrder = "asc";
        }

        $query = $this->pdo->prepare("SELECT Products.* FROM Products  JOIN categories ON category_id=categories.id WHERE title LIKE :q or categories.name LIKE :q  ORDER BY $sortCol $sortOrder"); // Products är TABELL
        $query->execute(['q' => "%$q%"]);
        return $query->fetchAll(PDO::FETCH_CLASS, 'Product');
    }


    
    function initDatabase() {
        $this->pdo->query('CREATE TABLE IF NOT EXISTS Categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) UNIQUE
        )');

        $this->pdo->query('CREATE TABLE IF NOT EXISTS Products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50),
            price INT,
            stockLevel INT,
            category_id INT,
            popularityFactor INT,
            FOREIGN KEY (category_id) REFERENCES Categories(id)
        )');
     
        $this->pdo->query('CREATE TABLE IF NOT EXISTS UserDetails(
        id INT PRIMARY KEY,
        name VARCHAR(50),
        streetAdress VARCHAR(100),
        postalCode VARCHAR(20),
        city VARCHAR(100)
        )');

    }

    function addUserDetails($id, $name, $streetAdress, $postalCode, $city) {
     $query = $this->pdo->prepare('INSERT INTO UserDetails (id, name, streetAdress, postalCode, city) VALUES (:id, :name, :streetAdress, :postalCode, :city)');
     $query->execute([
        'id' => $id,
        "name" => $name,
        "streetAdress" => $streetAdress,
        "postalCode" => $postalCode,
        "city" => $city
     ]);
    }

    function getProduct($id) {
        $query = $this->pdo->prepare("SELECT p.*, c.name AS categoryName FROM Products p JOIN Categories c ON p.category_id = c.id WHERE p.id = :id");
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        $product = new Product();
        foreach ($result as $key => $val) {
            $product->$key = $val;
        }
        return $product;
    }

    function updateProduct($product) {
        $categoryId = $this->getCategoryIdOrCreate($product->categoryName);
        $s = "UPDATE Products SET title = :title, price = :price, stockLevel = :stockLevel, category_id = :category_id, popularityFactor = :popularityFactor WHERE id = :id";
        $query = $this->pdo->prepare($s);
        $query->execute([
            'title' => $product->title,
            'price' => $product->price,
            'stockLevel' => $product->stockLevel,
            'category_id' => $categoryId,
            'popularityFactor' => $product->popularityFactor,
            'id' => $product->id
        ]);
    }

    function getCartItems($userId, $sessionId){
            if($userId != null ){
                $query = $this->pdo->prepare("UPDATE CartItem SET userId=:userId WHERE userId IS NULL AND  sessionId = :sessionId");
                $query->execute(['sessionId' => $sessionId, 'userId' => $userId]);
            }

            $query = $this->pdo->prepare("SELECT CartItem.Id as id, CartItem.productId, CartItem.quantity, Products.title as productName, Products.price as productPrice, Products.price * CartItem.quantity as rowPrice     FROM CartItem JOIN Products ON Products.id=CartItem.productId  WHERE userId=:userId or sessionId = :sessionId");
            $query->execute(['sessionId' => $sessionId, 'userId' => $userId]);


            return $query->fetchAll(PDO::FETCH_CLASS, 'CartItem');
        }

    function deleteProduct($id) {
        $query = $this->pdo->prepare("DELETE FROM Products WHERE id = :id");
        $query->execute(['id' => $id]);
    }
    
function updateCartItem($userId, $sessionId,$productId, $quantity){
            if($quantity <= 0){
                $query = $this->pdo->prepare("DELETE FROM CartItem WHERE (userId=:userId or sessionId=:sessionId) AND productId = :productId");
                $query->execute([ 'userId' => $userId, 'sessionId' => $sessionId, 'productId' => $productId]);
                return;
            }
            $query = $this->pdo->prepare("SELECT * FROM CartItem  WHERE (userId=:userId or sessionId=:sessionId) AND productId = :productId");
            $query->execute([ 'userId' => $userId, 'sessionId' => $sessionId, 'productId' => $productId]);
            if($query->rowCount() == 0){
                $query = $this->pdo->prepare("INSERT INTO CartItem (productId, quantity, sessionId, userId) VALUES (:productId, :quantity, :sessionId, :userId)");
                $query->execute([ 'userId' => $userId, 'sessionId' => $sessionId, 'productId' => $productId, 'quantity' => $quantity]);
            }
            else{
                $query = $this->pdo->prepare("UPDATE CartItem SET quantity = :quantity WHERE (userId=:userId or sessionId=:sessionId) AND productId = :productId");
                $query->execute([ 'userId' => $userId, 'sessionId' => $sessionId, 'productId' => $productId, 'quantity' => $quantity]);
            }
        }

    function insertProduct($title, $stockLevel, $price, $categoryName, $popularityFactor, $imageUrl = null) {
        $catId = $this->getCategoryIdOrCreate($categoryName);
        $sql = "INSERT INTO Products (title, price, stockLevel, category_id, popularityFactor, imageUrl)
                VALUES (:title, :price, :stockLevel, :category_id, :popularityFactor, :imageUrl)";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            'title' => $title,
            'price' => $price,
            'stockLevel' => $stockLevel,
            'category_id' => $catId,
            'popularityFactor' => $popularityFactor,
            'imageUrl' => $imageUrl
        ]);
    }
    
    

    function getAllProducts($sortCol = "id", $sortOrder = "asc") {
        if (!in_array($sortCol, ["id", "title", "price", "stockLevel", "popularityFactor"])) $sortCol = "id";
        if (!in_array($sortOrder, ["asc", "desc"])) $sortOrder = "asc";

        $query = $this->pdo->query("SELECT p.*, c.name AS categoryName FROM Products p JOIN Categories c ON p.category_id = c.id ORDER BY $sortCol $sortOrder");
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            $product = new Product();
            foreach ($row as $key => $val) {
                $product->$key = $val;
            }
            return $product;
        }, $results);
    }

    function getPopularProducts() {
        $query = $this->pdo->query("SELECT p.*, c.name AS categoryName FROM Products p JOIN Categories c ON p.category_id = c.id ORDER BY popularityFactor DESC LIMIT 10");
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            $product = new Product();
            foreach ($row as $key => $val) {
                $product->$key = $val;
            }
            return $product;
        }, $results);
    }

    

    function getCategoryProducts($catName) {
        if (empty($catName)) {
            return $this->getAllProducts();
        }
        
        $query = $this->pdo->prepare("SELECT p.*, c.name AS categoryName 
                                      FROM Products p 
                                      JOIN Categories c ON p.category_id = c.id 
                                      WHERE c.name = :categoryName");
        $query->execute(['categoryName' => $catName]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return array_map(function ($row) {
            $product = new Product();
            foreach ($row as $key => $val) {
                $product->$key = $val;
            }
            return $product;
        }, $results);
    }

    function getAllCategories() {
        return $this->pdo->query('SELECT name FROM Categories')->fetchAll(PDO::FETCH_COLUMN);
    }
}




?>
