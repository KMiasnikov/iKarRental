<?php
include 'includes/header.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = json_decode(file_get_contents('data/users.json'), true);
    $newUser = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
    ];
    $users[] = $newUser;
    file_put_contents('data/users.json', json_encode($users));
    echo "Registration successful!";
}
?>

<div class="registration">
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>