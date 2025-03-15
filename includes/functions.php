<?php
function getCarById($id) {
    $cars = loadCars();
    foreach ($cars as $car) {
        if ($car['id'] == $id) {
            return $car;
        }
    }
    return null;
}

function loadCars() {
    return json_decode(file_get_contents('data/cars.json'), true);
}

function loadCarById($id) {
    $cars = loadCars();
    foreach ($cars as $car) {
        if ($car['id'] == $id) return $car;
    }
    return null;
}

function loadBookings($carId) {
    $bookings = json_decode(file_get_contents('data/bookings.json'), true);
    return array_filter($bookings, function ($booking) use ($carId) {
        return $booking['car_id'] == $carId;
    });
}

function loadUserBookings($email) {
    $bookings = json_decode(file_get_contents('data/bookings.json'), true);
    return array_filter($bookings, function ($booking) use ($email) {
        return $booking['user_email'] === $email;
    });
}

function saveBooking($carId, $from, $until, $email) {
    $bookings = json_decode(file_get_contents('data/bookings.json'), true);
    $bookings[] = [
        'car_id' => $carId, 'from' => $from, 'until' => $until, 'user_email' => $email
    ];
    file_put_contents('data/bookings.json', json_encode($bookings, JSON_PRETTY_PRINT));
}

function deleteCar($id) {
    $cars = loadCars();
    $cars = array_filter($cars, fn($car) => $car['id'] != $id);
    file_put_contents('data/cars.json', json_encode(array_values($cars), JSON_PRETTY_PRINT));
}

function registerUser($name, $email, $password) {
    $users = json_decode(file_get_contents('data/users.json'), true);
    $users[] = ['name' => $name, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT), 'is_admin' => false];
    file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT));
}

function authenticateUser($email, $password) {
    $users = json_decode(file_get_contents('data/users.json'), true);
    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            return $user;
        }
    }
    return null;
}

function addCar($carData) {
    $cars = loadCars();
    $carData['id'] = count($cars) + 1;
    $cars[] = $carData;
    file_put_contents('data/cars.json', json_encode($cars, JSON_PRETTY_PRINT));
}

function updateCar($id, $carData) {
    $cars = loadCars();
    foreach ($cars as &$car) {
        if ($car['id'] == $id) {
            $car = array_merge($car, $carData);
            break;
        }
    }
    file_put_contents('data/cars.json', json_encode($cars, JSON_PRETTY_PRINT));
}
?>