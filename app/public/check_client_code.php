<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$request = json_decode(file_get_contents('php://input'), true);
$clientCode = $request['client_code'];

$query = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE client_code = :client_code");
$query->execute(['client_code' => $clientCode]);
$exists = $query->fetchColumn() > 0;

echo json_encode(['unique' => !$exists]);
