<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include '../db.php';

class CreateAccount {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $this->createAccount($data);
        }
    }

    private function createAccount($data) {
        $data['PinHash'] = password_hash($data['Pinhash'], PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare('INSERT INTO accounts (AccountNumber, firstname, lastname, Pinhash, Balance, isblocked) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([ $data['AccountNumber'], $data['FirstName'], $data['LastName'], $data['PinHash'], $data['Balance'], 0]);
        echo json_encode(['message' => 'Account created']);

        if (!$stmt) {
            echo json_encode(['message' => 'Error']);
        }
    }
}

$createAccount = new CreateAccount($pdo);
$createAccount->handleRequest();
?>