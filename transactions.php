<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include 'db.php';

class Transactions {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->getTransactions();
        }
    }

    private function getTransactions() {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['AccountId'];

        $stmt = $this->pdo->prepare('SELECT * FROM transactions WHERE AccountId = ? ORDER BY TransactionDate DESC LIMIT 5');
        $stmt->execute([$id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
    }
}

$transactions = new Transactions($pdo);
$transactions->handleRequest();
?>