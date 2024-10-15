<?php
class Client {
    private $pdo;

    public function __construct($dbConnection) {
        $this->pdo = $dbConnection; // Store the PDO instance
    }

    // Method to retrieve all clients
    public function getAllClients() {
        $stmt = $this->pdo->query("SELECT * FROM clients ORDER BY name ASC"); // Query to fetch all clients
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return array of clients
    }

    public function createClient($name, $code, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO clients (name, client_code, description) VALUES (:name, :client_code, :description)");
        $stmt->execute([
            ':name' => $name,
            ':client_code' => $code,
            ':description' => $description
        ]);
    }

    public function getNextClientCode($name) {
        $alphaPrefix = $this->generateAlphaPrefix($name);
        $suffix = 1;
        $fullCode = $alphaPrefix . str_pad($suffix, 3, '0', STR_PAD_LEFT);

        // Ensure the code is unique
        while ($this->clientCodeExists($fullCode)) {
            $suffix++;
            $fullCode = $alphaPrefix . str_pad($suffix, 3, '0', STR_PAD_LEFT);
        }

        return $fullCode;
    }

    private function generateAlphaPrefix($name) {
        $trimmedName = strtoupper(trim($name));
        $parts = explode(' ', $trimmedName);
        
        if (count($parts) > 1) {
            $alphaPrefix = substr($parts[0], 0, 1) . substr($parts[1], 0, 1);
        } else {
            $alphaPrefix = substr($trimmedName, 0, 3);
        }

        if (strlen($alphaPrefix) < 3) {
            while (strlen($alphaPrefix) < 3) {
                $alphaPrefix .= chr(65 + strlen($alphaPrefix) - 1);
            }
        }

        return strtoupper($alphaPrefix);
    }

    private function clientCodeExists($code) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM clients WHERE client_code = :client_code");
        $stmt->execute([':client_code' => $code]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
