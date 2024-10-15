<?php

require_once '../models/Client.php'; // Ensure you include the Client model

class ClientController {
    private $pdo;
    private $clientModel;

    public function __construct($dbConnection) {
        $this->pdo = $dbConnection;
        $this->clientModel = new Client($dbConnection); // Ensure the model is instantiated
    }

    // Define the index method to fetch clients    
    public function index() {
        $clients = $this->clientModel->getAllClients(); // Call the method to get all clients
        return $clients;  // You may want to return this to the view or handle it as necessary
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $contacts = $_POST['contacts'] ?? [];

            // Validate input
            if (empty($name)) {
                $errors[] = 'Name is required.';
            } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
                $errors[] = 'Name must only contain letters and spaces.';
            }

            if (empty($description)) {
                $errors[] = 'Description is required.';
            }

            if (empty($errors)) {
                $clientCode = $this->generateUniqueClientCode($name);
                $this->createClient($name, $clientCode, $description);

                // Link contacts if applicable
                if (!empty($contacts)) {
                    $this->linkContacts($clientCode, $contacts);
                }

                header("Location: " . BASE_URL . "client_list.php");
                exit();
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['form_data'] = $_POST;
                header("Location: " . BASE_URL . "client_form.php");
                exit();
            }
        }
    }

    public function getClient($clientId) {
        $query = $this->pdo->prepare("SELECT * FROM clients WHERE id = :id");
        $query->execute(['id' => $clientId]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getLinkedContacts($clientId) {
        // Fetch linked contacts from database
        $query = $this->pdo->prepare("SELECT c.id, c.full_name, c.email FROM contacts c JOIN client_contacts cc ON c.id = cc.contact_id WHERE cc.client_id = :client_id");
        $query->execute(['client_id' => $clientId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateUniqueClientCode($name) {
        $alphaPart = strtoupper(substr($name, 0, 3));
        $alphaPart = str_pad($alphaPart, 3, 'A');
        
        $query = $this->pdo->prepare("SELECT MAX(CAST(SUBSTRING(client_code, 4) AS UNSIGNED)) AS max_num FROM clients WHERE client_code LIKE :like_code");
        $query->execute(['like_code' => $alphaPart . '%']);
        $maxNum = $query->fetchColumn();

        $numericPart = str_pad(($maxNum !== null ? $maxNum + 1 : 1), 3, '0', STR_PAD_LEFT);
        return $alphaPart . $numericPart;
    }

    private function createClient($name, $clientCode, $description) {
        $query = $this->pdo->prepare("INSERT INTO clients (name, client_code, description) VALUES (:name, :client_code, :description)");
        $query->execute(['name' => $name, 'client_code' => $clientCode, 'description' => $description]);
    }

    private function linkContacts($clientCode, $contacts) {
        foreach ($contacts as $contactId) {
            $query = $this->pdo->prepare("INSERT INTO client_contacts (client_code, contact_id) VALUES (:client_code, :contact_id)");
            $query->execute(['client_code' => $clientCode, 'contact_id' => $contactId]);
        }
    }
}
?>






