<?php
require_once '../models/Contact.php'; // Ensure that the Contact model is included

class ContactController {
    private $pdo;
    private $contactModel;

    public function __construct($dbConnection) {
        $this->contactModel = new Contact($dbConnection); // Here is where the Contact class is instantiated
        $this->pdo = $dbConnection;
        return $this->contactModel->getAllContacts();

    }

    public function index() {
        // Fetch all contacts from the database
        $stmt = $this->pdo->query("SELECT * FROM contacts ORDER BY contact_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return the contacts array
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $fullName = $_POST['full_name'] ?? ''; // Fallback to an empty string if not set
            $email = $_POST['email'] ?? '';

            // Validation
            if (empty($fullName)) {
                $errors[] = 'Full Name is required.';
            } elseif (!preg_match("/^[a-zA-Z\s]+$/", $fullName)) {
                $errors[] = 'Full Name must only contain letters.';
            }

            if (empty($email)) {
                $errors[] = 'Email is required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email format is invalid.';
            }

            // If no errors, create contact
            if (empty($errors)) {
                $this->contactModel->createContact($fullName, $email);
                header("Location: " . BASE_URL . "contact_list.php");
                exit();
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['form_data'] = $_POST;
                header("Location: " . BASE_URL . "contact_form.php");
                exit();
            }
        }
    }
    
    public function getContact($contactId) {
        return $this->contactModel->getContactById($contactId);
    }
}
?>

