<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data
    $data = json_decode(file_get_contents("php://input"), true);

    $accnumber = $data['AccountNumber'];
    $pin = $data['PinHash'];

    // Retrieve account details using account number
    $stmt = $pdo->prepare('SELECT * FROM Accounts WHERE AccountNumber = ?');
    $stmt->execute([$accnumber]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the account exists
    if (!$result) {
        echo json_encode(['message' => 'Account not found', 'success' => false]);
        return;
    }

    // If account exists, verify the pin
    if (password_verify($pin, $result['PinHash'])) {
        $token = bin2hex(random_bytes(32));  // Generate a new token

        // first check if theres a token already for that account if not insert a new one else update the existing one
        $stmt = $pdo->prepare('SELECT * FROM Account_tokens WHERE AccountId = ?');
        $stmt->execute([$result['AccountId']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $stmt = $pdo->prepare('INSERT INTO Account_tokens (AccountId, Token) VALUES (?, ?)');
            $stmt->execute([$result['AccountId'], $token]);
        } else {
            $stmt = $pdo->prepare('UPDATE Account_tokens SET Token = ? WHERE AccountId = ?');
            $stmt->execute([$token, $result['AccountId']]);
        }
        // Store the token in session for further use
        $_SESSION['token'] = $token;

        // Respond with success and the generated token
        echo json_encode(['token' => $token, 'message' => 'Logged in' , 'success' => true]);
    } else {
        // Invalid pin response
        echo json_encode(['message' => 'Invalid Pin', 'success' => false]);
    }
}
?>
