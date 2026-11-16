<?php
require_once('models/Product.php');
require_once("components/footer.php");
require_once('models/Database.php');
require_once("components/navbar.php");
require_once('utils/validator.php');

$dbContext = new Database();

$data = $_POST ?? [];
$errorMessages = [];
$valid = new Validator($data);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email        = $data['email'];
    $password     = $data['password'];
    $passwordRepeat = $data['passwordRepeat'];
    $name         = $data['name'];
    $streetAdress = $data['streetAdress'];
    $postalCode   = $data['postalCode'];
    $city         = $data['city'];

    $valid->field('email')->required()->email();
    $valid->field('password')->required()->min_len(7)->max_len(20);
    $valid->field('passwordRepeat')->equals($password);
    $valid->field('name')->required()->min_len(3)->max_len(50);
    $valid->field('streetAdress')->required()->min_len(3)->max_len(50);
    $valid->field('postalCode')->required()->max_len(5);
    $valid->field('city')->required()->max_len(50);

    if (!$valid->is_valid()) {
        $errorMessages = $valid->error_messages;
    } else {
        try {
            $userId = $dbContext
                ->getUsersDatabase()
                ->getAuth()
                ->register($email, $password, $name);

            $dbContext->addUserDetails($userId, $name, $streetAdress, $postalCode, $city);
            header('Location: /user/registerDone');
            exit;

        } catch (\Delight\Auth\InvalidEmailException $e) {
            $errorMessages['email'] = 'Invalid email address.';
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $errorMessages['password'] = 'Password does not meet requirements.';
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $errorMessages['email'] = 'An account with this email already exists.';
        } catch (Exception $e) {
            error_log($e->getMessage());
            $errorMessages['general'] = 'Something went wrong, please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/variables.css">
    <link rel="stylesheet" href="/styles/global.css">
    <link rel="stylesheet" href="/styles/createAccount.css">
</head>
<body>
<?php navBar(); ?>

<section class="register-section">
    <div class="auth-card">

        <div>
            <h1 class="auth-heading">Join the Strawhats.</h1>
            <p class="auth-sub">Create your account and set sail.</p>
        </div>

        <?php if (isset($errorMessages['general'])): ?>
            <div class="auth-error"><?= htmlspecialchars($errorMessages['general']) ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form">

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" value="<?= htmlspecialchars($data['email'] ?? '') ?>">
                <?php if (!empty($errorMessages['email'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errorMessages['email']) ?></span>
                <?php endif; ?>
            </div>

            <div class="register-grid">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Min. 7 characters">
                    <?php if (!empty($errorMessages['password'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errorMessages['password']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="passwordRepeat">Confirm password</label>
                    <input type="password" id="passwordRepeat" name="passwordRepeat" placeholder="Repeat password">
                    <?php if (!empty($errorMessages['passwordRepeat'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errorMessages['passwordRepeat']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="name">Full name</label>
                <input type="text" id="name" name="name" placeholder="Monkey D. Luffy" value="<?= htmlspecialchars($data['name'] ?? '') ?>">
                <?php if (!empty($errorMessages['name'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errorMessages['name']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="streetAdress">Street address</label>
                <input type="text" id="streetAdress" name="streetAdress" placeholder="1 Foosha Village Rd" value="<?= htmlspecialchars($data['streetAdress'] ?? '') ?>">
                <?php if (!empty($errorMessages['streetAdress'])): ?>
                    <span class="field-error"><?= htmlspecialchars($errorMessages['streetAdress']) ?></span>
                <?php endif; ?>
            </div>

            <div class="register-grid">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" placeholder="East Blue" value="<?= htmlspecialchars($data['city'] ?? '') ?>">
                    <?php if (!empty($errorMessages['city'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errorMessages['city']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="postalCode">Postal code</label>
                    <input type="text" id="postalCode" name="postalCode" placeholder="12345" value="<?= htmlspecialchars($data['postalCode'] ?? '') ?>">
                    <?php if (!empty($errorMessages['postalCode'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errorMessages['postalCode']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn-register">Create Account</button>

        </form>

        <div class="auth-links">
            <span>Already have an account? <a href="/user/login">Log in</a></span>
        </div>

    </div>
</section>

<?php Footer(); ?>
</body>
</html>
