<?php
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    if ($email && $password && $password === $password2) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
            $stmt->execute([$email, $hash]);
            header('Location: login.php');
            exit;
        } catch (Exception $e) {
            $error = 'User already exists';
        }
    } else {
        $error = 'Please fill all fields correctly';
    }
}
?>
<div class="register">
    <h2>Register</h2>
    <?php if (!empty($error)) echo '<p>'.h($error).'</p>'; ?>
    <form method="post">
        <input type="email" name="email" placeholder="email" required>
        <input type="password" name="password" placeholder="password" required>
        <input type="password" name="password2" placeholder="password again" required>
        <button type="submit">register</button>
    </form>
    <a href="login.php">Already have an account?</a>
</div>
<?php require 'footer.php'; ?>
