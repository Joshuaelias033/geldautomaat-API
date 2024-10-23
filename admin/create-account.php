<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new account with the given firstname lastname pincode and balance and is isblocked
    $data = json_decode(file_get_contents('php://input'), true);
    $data['PinHash'] = password_hash($data['Pinhash'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('INSERT INTO accounts (AccountNumber, firstname, lastname, Pinhash, Balance, isblocked) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([ $data['AccountNumber'], $data['FirstName'], $data['LastName'], $data['PinHash'], $data['Balance'], 0]);
    echo json_encode(['message' => 'Account created']);

    if (!$stmt) {
        echo json_encode(['message' => 'Error']);
    }
}

?>