<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include '../db.php';

class DeleteAccount {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $this->deleteAccount($input);
        }
    }

    private function deleteAccount($input) {
        $stmt = $this->pdo->prepare('DELETE FROM accounts WHERE Accountid = ?');
        $stmt->execute([$input['AccountId']]);
        echo json_encode(['message' => 'Account deleted']);
    }
}

$deleteAccount = new DeleteAccount($pdo);
$deleteAccount->handleRequest();
?>