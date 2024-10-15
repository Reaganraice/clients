<?php
session_start();
require_once '../../config/config.php';
require_once '../../app/controllers/ContactController.php';
require_once '../../app/models/Contact.php'; // Include the Contact model


require_once '../../config/database.php';

if (isset($pdo)) {
    echo "Database connection established.";
} else {
    echo "Database connection failed.";
}



$controller = new ContactController($pdo);
$contactId = $_GET['contactId'] ?? null;

// Load existing contact data if in edit mode
$contactData = null;
if ($contactId) {
    $contactData = $controller->getContact($contactId); // Add this method to your controller
}
$fullName = $contactData['full_name'] ?? '';
$email = $contactData['email'] ?? '';

$errors = $_SESSION['errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['errors'], $_SESSION['form_data']); // Clear the session after using
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $contactId ? 'Edit Contact' : 'Create Contact'; ?></title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <h1><?php echo $contactId ? 'Edit Contact' : 'Create New Contact'; ?></h1>

    <?php if ($errors): ?>
        <div style="color: red;">
            <strong>Errors:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($fullName); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <button type="submit">Add Contact</button>
    </form>
</body>
</html>
