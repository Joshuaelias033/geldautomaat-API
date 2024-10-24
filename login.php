<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['AccountNumber']) || !isset($data['PinHash'])) {
        echo json_encode(['message' => 'Invalid input', 'success' => false]);
        exit;
    }

    $accnumber = $data['AccountNumber'];
    $pin = $data['PinHash'];

    // Retrieve account details using account number
    $stmt = $pdo->prepare('SELECT * FROM Accounts WHERE AccountNumber = ?');
    $stmt->execute([$accnumber]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the account exists
    if (!$account) {
        echo json_encode(['message' => 'Account not found', 'success' => false]);
        exit;
    }

    // If account exists, verify the pin
    if (password_verify($pin, $account['PinHash'])) {
        $token = bin2hex(random_bytes(32));  // Generate a new token

        // Check if there is already a token for that account
        $stmt = $pdo->prepare('SELECT * FROM Account_tokens WHERE AccountId = ?');
        $stmt->execute([$account['AccountId']]);
        $existingToken = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingToken) {
            // Insert a new token if no token exists
            $stmt = $pdo->prepare('INSERT INTO Account_tokens (AccountId, Token) VALUES (?, ?)');
            $stmt->execute([$account['AccountId'], $token]);
        } else {
            // Update the existing token
            $stmt = $pdo->prepare('UPDATE Account_tokens SET Token = ? WHERE AccountId = ?');
            $stmt->execute([$token, $account['AccountId']]);
        }

        // Store the token in the session for further use
        $_SESSION['token'] = $token;

        // Respond with success and the generated token
        echo json_encode(['token' => $token, 'message' => 'Logged in', 'success' => true]);
    } else {
        // Invalid pin response
        echo json_encode(['message' => 'Invalid Pin', 'success' => false]);
    }
}
?>
