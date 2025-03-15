<?php
include 'includes/header.php';
require 'storage.php';
include 'includes/functions.php';

$cars = json_decode(file_get_contents('data/cars.json'), true);
$bookings = json_decode(file_get_contents('data/bookings.json'), true);

$filters = [
    'transmission' => $_GET['transmission'] ?? null,
    'min_price' => $_GET['min_price'] ?? null,
    'max_price' => $_GET['max_price'] ?? null,
    'passengers' => $_GET['passengers'] ?? null,
    'start_date' => $_GET['start_date'] ?? null,
    'end_date' => $_GET['end_date'] ?? null
];

$filteredCars = array_filter($cars, function($car) use ($filters, $bookings) {
    if ($filters['transmission'] && $car['transmission'] !== $filters['transmission']) {
        return false;
    }
    if ($filters['min_price'] && $car['daily_price_huf'] < $filters['min_price']) {
        return false;
    }
    if ($filters['max_price'] && $car['daily_price_huf'] > $filters['max_price']) {
        return false;
    }
    if ($filters['passengers'] && $car['passengers'] < $filters['passengers']) {
        return false;
    }
    if ($filters['start_date'] && $filters['end_date']) {
        foreach ($bookings as $booking) {
            if ($booking['car_id'] == $car['id'] &&
                max(strtotime($filters['start_date']), strtotime($booking['start_date'])) < 
                min(strtotime($filters['end_date']), strtotime($booking['end_date']))) {
                return false;
            }
        }
    }
    return true;
});

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iKarRental - Home</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="available-cars">
        <h1>Available Cars</h1>
        <form method="GET">
            <select name="transmission">
                <option value="">Any Transmission</option>
                <option value="Automatic">Automatic</option>
                <option value="Manual">Manual</option>
            </select>
            <input type="number" name="min_price" placeholder="Min 12000 HUF/Day">
            <input type="number" name="max_price" placeholder="Max 48000 HUF/Day">
            <input type="number" name="passengers" placeholder="Min Passengers">
            <label>Start Date: <input type="date" name="start_date"></label>
            <label>End Date: <input type="date" name="end_date"></label>
            <button type="submit">Filter</button>
        </form>
    </div>
    <div class="car-list">
        <?php foreach ($filteredCars as $car): ?>
        <div class="car-card">
            <a href="car.php?id=<?= $car['id'] ?>"><img src="<?= $car['image'] ?>" alt="<?= $car['brand'] ?>"></a>
            <a href="car.php?id=<?= $car['id'] ?>"><h2><?= $car['brand'] ?> <?= $car['model'] ?></h2></a>
            <p>Transmission: <?= $car['transmission'] ?></p>
            <p>Price: <?= $car['daily_price_huf'] ?> HUF/day</p>
            <a href="car.php?id=<?= $car['id'] ?>">View Details</a>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>