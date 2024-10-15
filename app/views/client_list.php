<?php
// Example of how you might fetch clients
// $clients = $controller->index(); // Assuming this retrieves clients

// Check if there are any clients
if (empty($clients)) {
    echo "<h2>No client(s) found.</h2>";
} else {
    // Include your table headers, e.g.:
    echo "<table>";
    echo "<tr><th>Name</th><th>Client Code</th><th>Description</th></tr>";

    // Loop through clients to display them
    foreach ($clients as $client) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($client['name']) . "</td>";
        echo "<td>" . htmlspecialchars($client['client_code']) . "</td>";
        echo "<td>" . htmlspecialchars($client['description']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client List</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <h1>Client List</h1>
    <a href="client_form.php">Create New Client</a>
    <?php if (!empty($clients)): ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Client Code</th>
            <th>Description</th>
        </tr>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?php echo htmlspecialchars($client['name']); ?></td>
                <td><?php echo htmlspecialchars($client['client_code']); ?></td>
                <td><?php echo htmlspecialchars($client['description']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <h2>No clients found.</h2>
<?php endif; ?>
</body>
</html>





