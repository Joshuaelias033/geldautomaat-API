<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include '../db.php';

class Accounts {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getAccounts();
        }
    }

    private function getAccounts() {
        $stmt = $this->pdo->prepare('SELECT * FROM Accounts');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    }
}

$accounts = new Accounts($pdo);
$accounts->handleRequest();
?>