<?php
class Contact {
    private $pdo;

    public function __construct($dbConnection) {
        $this->pdo = $dbConnection;
    }

    public function createContact($fullName, $email) {
        $stmt = $this->pdo->prepare("INSERT INTO contacts (full_name, email) VALUES (:full_name, :email)");
        $stmt->execute(['full_name' => $fullName, 'email' => $email]);
    }

    public function getAllContacts() {
        $stmt = $this->pdo->query("SELECT id, full_name, email FROM contacts ORDER BY full_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
