<?php
include 'includes/header.php';
require 'storage.php';
include 'includes/functions.php';

$cars = json_decode(file_get_contents('data/cars.json'), true);
$id = $_GET['id'] ?? null;

$car = array_values(array_filter($cars, fn($c) => $c['id'] == $id))[0] ?? null;
if (!$car) {
    echo "Car not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Details</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="car-details">
        <h1><?= $car['brand'] ?> <?= $car['model'] ?></h1>
        <img src="<?= $car['image'] ?>" alt="<?= $car['brand'] ?>">
        <div class="cars-info">
            <p>Year: <?= $car['year'] ?></p>
            <p>Transmission: <?= $car['transmission'] ?></p>
            <p>Fuel Type: <?= $car['fuel_type'] ?></p>
            <p>Passengers: <?= $car['passengers'] ?></p>
            <p>Price: <?= $car['daily_price_huf'] ?> HUF/day</p>
        </div>
        <form action="book.php" method="POST">
            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
            <label>Start Date: <input type="date" name="start_date"></label>
            <label>End Date: <input type="date" name="end_date"></label><br><br>
            <button type="submit">Book Now</button>
        </form>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>