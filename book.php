<?php
include 'includes/header.php';
include 'includes/functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
    $carId = $_POST['car_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    if (empty($startDate) || empty($endDate)) {
        echo "<p>Error: Both start date and end date are required.</p>";
        echo '<a href="car.php?id=' . $carId . '">Go Back</a>';
        exit;
    }

    if (strtotime($startDate) >= strtotime($endDate)) {
        echo "<p>Error: Start date must be before end date.</p>";
        echo '<a href="car.php?id=' . $carId . '">Go Back</a>';
        exit;
    }

    $bookings = json_decode(file_get_contents('data/bookings.json'), true);
    $conflict = false;
    foreach ($bookings as $booking) {
        if ($booking['car_id'] == $carId && max(strtotime($startDate), strtotime($booking['start_date'])) < min(strtotime($endDate), strtotime($booking['end_date']))) {
            $conflict = true;
            break;
        }
    }

    if ($conflict) {
        echo "<p>Booking failed! The car is not available for the selected dates.</p>";
        echo '<a href="index.php">Go Back</a>';
    } else {
        $bookings[] = [
            'car_id' => $carId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_email' => $_SESSION['user']['email'],
            'user_name' => $_SESSION['user']['name']
        ];
        file_put_contents('data/bookings.json', json_encode($bookings));
        echo "<p>Booking successful!</p>";
        echo '<a href="profile.php">View Your Bookings</a>';
    }
}

include 'includes/footer.php';
?>