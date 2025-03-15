<?php
include 'includes/header.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = json_decode(file_get_contents('data/users.json'), true);
    foreach ($users as $user) {
        if ($user['email'] === $_POST['email'] && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit;
        }
    }
    echo "Invalid credentials!";
}
?>

<div class="login">
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>