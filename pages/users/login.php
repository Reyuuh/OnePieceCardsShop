<?php
require_once('models/Product.php');
require_once("components/footer.php");
require_once('models/Database.php');
require_once("components/navbar.php");

$dbContext = new Database();

$errorMessage = "";
$username = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $dbContext->getUsersDatabase()->getAuth()->login($username, $password);
        header('Location: /');
        exit;
    } catch (Exception $e) {
        $errorMessage = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/variables.css">
    <link rel="stylesheet" href="/styles/global.css">
    <link rel="stylesheet" href="/styles/login.css">
</head>
<body>
<?php navBar(); ?>

<section class="login-section">
    <div class="auth-card">

        <div>
            <h1 class="auth-heading">Welcome back, Strawhat.</h1>
            <p class="auth-sub">Log in to your account to continue.</p>
        </div>

        <?php if ($errorMessage): ?>
            <div class="auth-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">Email</label>
                <input type="email" id="username" name="username" placeholder="you@example.com" value="<?= htmlspecialchars($username) ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••">
            </div>

            <button type="submit" class="btn-login">Log In</button>
        </form>

        <div class="auth-links">
            <span>Don't have an account? <a href="/register">Create one</a></span>
            <a href="/forgot">Forgot your password?</a>
        </div>

    </div>
</section>

<?php Footer(); ?>
</body>
</html>
