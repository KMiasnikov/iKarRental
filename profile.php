<?php
include 'includes/header.php';
include 'includes/functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$bookings = json_decode(file_get_contents('data/bookings.json'), true);
$cars = json_decode(file_get_contents('data/cars.json'), true);

$userBookings = array_filter($bookings, function($booking) {
    return $booking['user_email'] === $_SESSION['user']['email'];
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="profile">
        <h1>Welcome, <?= $_SESSION['user']['name'] ?></h1>
        <h2>Your Bookings:</h2>
        <?php if (count($userBookings) > 0): ?>
            <ul>
                <?php foreach ($userBookings as $booking): ?>
                    <?php
                    $car = array_values(array_filter($cars, fn($c) => $c['id'] == $booking['car_id']))[0];
                    ?>
                    <li>
                        <strong><?= $car['brand'] ?> <?= $car['model'] ?></strong><br>
                        Dates: <?= $booking['start_date'] ?> to <?= $booking['end_date'] ?><br>
                        Price: <?= (strtotime($booking['end_date']) - strtotime($booking['start_date'])) / 86400 * $car['daily_price_huf'] ?> HUF
                    </li><br>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You have no bookings.</p><br>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>