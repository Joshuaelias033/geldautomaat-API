<?php
include 'db.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");

class UserData {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $this->getUserData($data);
        }
    }

    private function getUserData($data) {
        $stmt = $this->pdo->prepare('SELECT * FROM account_tokens WHERE Token = ?');
        $stmt->execute([$data['token']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->prepare('SELECT * FROM Accounts WHERE AccountId = ?');
        $stmt->execute([$result['AccountId']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($result);
    }
}

$userData = new UserData($pdo);
$userData->handleRequest();
?>