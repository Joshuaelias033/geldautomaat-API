<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data
    $data = json_decode(file_get_contents("php://input"), true);

    $accnumber = $data['AccountNumber'];
    $pin = $data['PinHash'];
    $stmt = $pdo->prepare('SELECT * FROM Accounts WHERE AccountNumber = ?');
    $stmt->execute([$accnumber]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result){        
        // Verify the pin
        if (password_verify($pin, $result['PinHash'])) {
            //create a token for the user in the database theres a column for token
            $token = bin2hex(random_bytes(32));
            $stmt = $pdo->prepare('UPDATE account_tokens SET Token = ? WHERE Accountid = ?');
            $stmt->execute([$token, $accnumber]);
            echo json_encode(['token' => $token, 'success' => true]);
        } else {
            echo json_encode(['message' => 'Invalid Pin', 'success' => false]);
        }
    }
}
?>
