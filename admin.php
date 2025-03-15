<?php
include 'includes/header.php';
include 'includes/functions.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['email'] !== 'admin@ikarrental.hu') {
    header('Location: login.php');
    exit;
}

$cars = json_decode(file_get_contents('data/cars.json'), true);
$bookings = json_decode(file_get_contents('data/bookings.json'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_car'])) {
    $errors = [];
    
    if (empty($_POST['brand']) || empty($_POST['model']) || empty($_POST['year']) || empty($_POST['transmission']) || empty($_POST['fuel_type']) || empty($_POST['passengers']) || empty($_POST['daily_price_huf']) || empty($_POST['image'])) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        $cars[] = [
            'id' => count($cars) + 1,
            'brand' => $_POST['brand'],
            'model' => $_POST['model'],
            'year' => $_POST['year'],
            'transmission' => $_POST['transmission'],
            'fuel_type' => $_POST['fuel_type'],
            'passengers' => $_POST['passengers'],
            'daily_price_huf' => $_POST['daily_price_huf'],
            'image' => $_POST['image']
        ];
        file_put_contents('data/cars.json', json_encode($cars, JSON_PRETTY_PRINT));
        echo "<p>Car added successfully!</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_car_id'])) {
    $cars = array_filter($cars, fn($c) => $c['id'] != $_POST['delete_car_id']);
    file_put_contents('data/cars.json', json_encode($cars));
    echo "<p>Car deleted successfully!</p>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_all_bookings'])) {
    file_put_contents('data/bookings.json', json_encode([]));
    echo "<p>All bookings have been deleted successfully!</p>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_booking_id'])) {
    $bookings = array_filter($bookings, fn($b) => $b['car_id'] != $_POST['delete_booking_id']);
    file_put_contents('data/bookings.json', json_encode(array_values($bookings)));
    echo "<p>Booking deleted successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="admin">
        <h1>Admin Dashboard</h1>
        <section class="add-new-car">
            <h2>Add a New Car:</h2>
            <form method="POST">
                <div class="add-car"></div>
                <input type="hidden" name="add_car" value="1">
                <label>Brand: <input type="text" name="brand" required></label><br><br>
                <label>Model: <input type="text" name="model" required></label><br><br>
                <label>Year: <input type="number" name="year" required></label><br><br>
                <label>Transmission: <select name="transmission" required>
                    <option value="Automatic">Automatic</option>
                    <option value="Manual">Manual</option>
                </select></label><br><br>
                <label>Fuel Type: <input type="text" name="fuel_type" required></label><br><br>
                <label>Passengers: <input type="number" name="passengers" required></label><br><br>
                <label>Daily Price (HUF): <input type="number" name="daily_price_huf" required></label><br><br>
                <label>Image URL: <input type="url" name="image" required></label><br><br>
                <button type="submit">Add Car</button>
            </div>
            </form>
        </section>

        <section class="bookings-manager">
            <div class="bookings-list">
                <h2>All Bookings:</h2>
                <?php if (count($bookings) > 0): ?>
                    <ul>
                        <?php foreach ($bookings as $booking): ?>
                            <li>
                                Car ID: <?= $booking['car_id'] ?>, User Email: <?= $booking['user_email'] ?>, Dates: <?= $booking['start_date'] ?> to <?= $booking['end_date'] ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_booking_id" value="<?= $booking['car_id'] ?>">
                                    <a href="admin.php"><button type="submit">Delete Booking</button></a>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No bookings found.</p>
                <?php endif; ?>
            </div>

            <?php if (count($bookings) > 0): ?>
                <div class="delete-all-bookings">
                    <form method="POST">
                        <input type="hidden" name="delete_all_bookings" value="1">
                        <a href="admin.php"><button type="submit">Delete All Bookings</button></a>
                    </form>
                </div>
            <?php endif; ?>
        </section>
        
        <div class="edit-car">
            <h2>Edit Car:</h2>
            <form method="POST">
                <input type="hidden" name="edit_car" value="1">
                <label>Select Car:
                    <select name="car_id" required>
                        <option value="">Select a car</option>
                        <?php foreach ($cars as $car): ?>
                            <option value="<?= $car['id'] ?>"><?= $car['brand'] ?> <?= $car['model'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </label><br><br>
                <label>Brand: <input type="text" name="brand" required></label><br><br>
                <label>Model: <input type="text" name="model" required></label><br><br>
                <label>Year: <input type="number" name="year" required></label><br><br>
                <label>Transmission: <select name="transmission" required>
                    <option value="Automatic">Automatic</option>
                    <option value="Manual">Manual</option>
                </select></label><br><br>
                <label>Fuel Type: <input type="text" name="fuel_type" required></label><br><br>
                <label>Passengers: <input type="number" name="passengers" required></label><br><br>
                <label>Daily Price (HUF): <input type="number" name="daily_price_huf" required></label><br><br>
                <label>Image URL: <input type="url" name="image" required></label><br><br>
                <button type="submit">Update Car</button>
            </form>
        </div>

        <div class="managecars">
            <h2>Delete Cars:</h2>
            <div class="car-manager">
                <?php foreach ($cars as $car): ?>
                    <div class="car-item">
                        <img src="<?= $car['image'] ?>" alt="<?= $car['brand'] ?>">
                        <h2><?= $car['brand'] ?> <?= $car['model'] ?></h2>
                        <form method="POST">
                            <input type="hidden" name="delete_car_id" value="<?= $car['id'] ?>">
                            <button type="submit">Delete Car</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php include 'includes/footer.php'; ?>
