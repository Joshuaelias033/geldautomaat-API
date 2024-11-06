<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include 'db.php';
session_start();

class Login {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $this->login($data);
        }
    }

    private function login($data) {
        if (!isset($data['AccountNumber']) || !isset($data['PinHash'])) {
            echo json_encode(['message' => 'Invalid input', 'success' => false]);
            exit;
        }

        $accnumber = $data['AccountNumber'];
        $pin = $data['PinHash'];

        $stmt = $this->pdo->prepare('SELECT * FROM Accounts WHERE AccountNumber = ?');
        $stmt->execute([$accnumber]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$account) {
            echo json_encode(['message' => 'Account not found', 'success' => false]);
            exit;
        }

        if ($account['IsBlocked'] == 1) {
            echo json_encode(['message' => 'Account is blocked', 'success' => false]);
            exit;
        }

        if (password_verify($pin, $account['PinHash'])) {
            $token = bin2hex(random_bytes(32));

            $stmt = $this->pdo->prepare('SELECT * FROM Account_tokens WHERE AccountId = ?');
            $stmt->execute([$account['AccountId']]);
            $existingToken = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingToken) {
                $stmt = $this->pdo->prepare('INSERT INTO Account_tokens (AccountId, Token) VALUES (?, ?)');
                $stmt->execute([$account['AccountId'], $token]);
            } else {
                $stmt = $this->pdo->prepare('UPDATE Account_tokens SET Token = ? WHERE AccountId = ?');
                $stmt->execute([$token, $account['AccountId']]);
            }

            $_SESSION['token'] = $token;

            echo json_encode(['token' => $token, 'message' => 'Logged in', 'success' => true]);
        } else {
            echo json_encode(['message' => 'Invalid Pin', 'success' => false]);
        }
    }
}

$login = new Login($pdo);
$login->handleRequest();
?>
