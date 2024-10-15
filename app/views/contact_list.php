<?php
// Example of how you might fetch contacts
// $contacts = $contactController->index(); // Assuming this retrieves contacts

// Check if there are any contacts
if (empty($contacts)) {
    echo "<h2>No contact(s) found.</h2>";
} else {
    // Include your table headers, e.g.:
    echo "<table>";
    echo "<tr><th>Full Name</th><th>Email</th></tr>";

    // Loop through contacts to display them
    foreach ($contacts as $contact) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($contact['full_name']) . "</td>";
        echo "<td>" . htmlspecialchars($contact['email']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact List</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <h1>Contact List</h1>
    <a href="contact_form.php">Create New Contact</a>
    <?php if (!empty($contacts)): ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
        <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?php echo htmlspecialchars($contact['contact_name']); ?></td>
                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                <td><?php echo htmlspecialchars($contact['phone']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <h2>No contacts found.</h2>
<?php endif; ?>
</body>
</html>



