<?php
session_start();
require_once __DIR__ . '/../../config/config.php'; // Ensure configuration is loaded
require_once __DIR__ . '/../../config/database.php'; // This is necessary to load the database connection

// Check if $pdo is defined correctly
// Make sure your database.php file includes something like this:
if (!isset($pdo)) {
    die("Database connection not established.");
}

require_once __DIR__ . '/../../app/controllers/ClientController.php';  
require_once __DIR__ . '/../../app/controllers/ContactController.php'; 

$controller = new ClientController($pdo);
$contactController = new ContactController($pdo); // Ensure $pdo is passed to the constructor
$clientController = new ClientController($pdo);


// Check if a specific action should be performed, here we check for a GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // You might want to add logic here to distinguish between different views
    // For example, if you want to list clients:
    $clients = $clientController->index(); // Fetch the list of clients
    // var_dump($clients); // Helps see if the clients are being retrieved correctly
    // die;
    // Now include the client_list view and pass the clients to it
    include  __DIR__ .'../../views/client_list.php'; // Correct path assuming this structure
    exit();
} else {
    // Handle POST requests or other actions here
}

// Default action to show clients
// Redirect to client_list.php in the views directory
header("Location: ../views/client_list.php"); 
exit();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <link rel="stylesheet" href="../../css/styles.css"> <!-- Adjust the path as needed -->
</head>
<body>
    <h1>Welcome to the Client Management System</h1>
    <!-- Your content goes here -->
</body>
</html>
