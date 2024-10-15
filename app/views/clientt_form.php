<?php
session_start();
require_once __DIR__.'../../../config/config.php';
require_once '../../config/database.php'; // Adjust the path according to your folder structure
require_once '../../app/controllers/ClientController.php';

$controller = new ClientController($pdo);
$clientCode = ''; // Default value for new clients
$clientId = $_GET['clientId'] ?? null; // Check if there's a clientId passed

// Function to populate form data if editing an existing client
function getClientData($clientId, $controller) {
    return $clientId ? $controller->getClient($clientId) : null;
}

// Load existing client data if in edit mode
$clientData = getClientData($clientId, $controller);
$name = $clientData['name'] ?? '';
$description = $clientData['description'] ?? '';
$clientCode = $clientData['client_code'] ?? '';

// Handle form submission or error messages
$errors = $_SESSION['errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['errors'], $_SESSION['form_data']); // Clear the session after using
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $clientId ? 'Edit Client' : 'Create Client'; ?></title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../public/js/validate.js" defer></script> <!-- Link to your JS for validation -->
</head>
<body>
    <h1><?php echo $clientId ? 'Edit Client' : 'Create New Client'; ?></h1>

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

    <form id="clientForm" action="index.php" method="POST">
        <div>
            <button type="button" onclick="showTab('generalTab')">General</button>
            <button type="button" onclick="showTab('contactTab')">Link Contacts</button>
        </div>

        <div id="generalTab" class="tab active">
            <h2>General Information</h2>
            <label for="description">Description:</label>
            <textarea name="description" rows="2" required><?php echo htmlspecialchars($description); ?></textarea>

            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="client_code">Client Code:</label>
            <input type="text" name="client_code" value="<?php echo htmlspecialchars($clientCode); ?>" readonly>

            <label>Type:</label>
            <input type="text" value="Textbox" readonly>
            <input type="text" value="Textbox" readonly>

            <label>Compulsory:</label>
            <input type="text" value="Yes" readonly>
            <input type="text" value="Yes" readonly>

            <label>Additional Information:</label>
            <input type="text" value="N/A" readonly>
            <textarea rows="2" readonly></textarea>
        </div>

        <div id="contactTab" class="tab">
            <h2>Link Contacts</h2>
            <label for="contacts">Select Contacts:</label>
            <select name="contacts[]" multiple>
                <?php
                $contactModel = new Contact($pdo);
                $allContacts = $contactModel->getAllContacts();
                foreach ($allContacts as $contact) {
                    echo '<option value="' . $contact['id'] . '">' . htmlspecialchars($contact['full_name']) . '</option>';
                }
                ?>
            </select>

            <h3>Linked Contacts</h3>
            <table>
                <tr>
                    <th>Contact Full Name</th>
                    <th>Email Address</th>
                    <th>Action</th>
                </tr>
                <?php
                if ($clientId) {
                    $linkedContacts = $controller->getLinkedContacts($clientId);
                    if (empty($linkedContacts)) {
                        echo '<tr><td colspan="3" style="text-align: center;">No contacts found.</td></tr>';
                    } else {
                        foreach ($linkedContacts as $linkedContact) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($linkedContact['full_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($linkedContact['email']) . '</td>';
                            echo '<td><a href="unlink_contact.php?client_id=' . $clientId . '&contact_id=' . $linkedContact['id'] . '">Unlink</a></td>';
                            echo '</tr>';
                        }
                    }
                }
                ?>
            </table>
        </div>

        <button type="submit">Add Client</button>
    </form>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>
</html>
