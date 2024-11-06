<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include '../db.php';

class EditAccount {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $this->editAccount($input);
        }
    }

    private function editAccount($input) {
        $stmt = $this->pdo->prepare('UPDATE accounts SET firstname = ?, lastname = ?, AccountNumber = ?, Balance = ?, IsBlocked = ? WHERE Accountid = ?');
        $stmt->execute([$input['FirstName'], $input['LastName'], $input['AccountNumber'], $input['Balance'], $input['IsBlocked'], $input['AccountId']]);
        echo json_encode(['message' => 'Account updated']);
    }
}

$editAccount = new EditAccount($pdo);
$editAccount->handleRequest();

?>