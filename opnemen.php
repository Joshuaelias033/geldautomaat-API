<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include 'db.php';

class Withdrawal {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $this->withdraw($data);
        }
    }

    private function withdraw($data) {
        $accountId = $data['AccountId'];
        $amount = $data['Amount'];
        $transactionDate = $data['TransactionDate'];
        $transactionType = 'Withdrawal';

        $sql = "INSERT INTO transactions (AccountId, Amount, TransactionDate, TransactionType) VALUES
        (:AccountId, :Amount, :TransactionDate, :TransactionType)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['AccountId' => $accountId, 'Amount' => $amount, 'TransactionDate' => $transactionDate, 'TransactionType' => $transactionType]);

        if (!$stmt){
            echo json_encode(['message' => 'Withdrawal failed']);
            return;
        }

        $sql = "UPDATE accounts SET Balance = Balance - :Amount WHERE AccountId = :AccountId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['Amount' => $amount, 'AccountId' => $accountId]);

        echo json_encode(['message' => 'Withdrawal successful']);
    }
}

$withdrawal = new Withdrawal($pdo);
$withdrawal->handleRequest();
?>