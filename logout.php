<?php
include 'includes/header.php';
include 'includes/functions.php';

session_destroy();
header('Location: index.php');
exit();

include 'includes/footer.php';
?>