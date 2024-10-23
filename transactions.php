<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['AccountId'];

    // get the last 5 transactions
    $stmt = $pdo->prepare('SELECT * FROM transactions WHERE AccountId = ? ORDER BY TransactionDate DESC LIMIT 5');
    $stmt->execute([$id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode($result);  
}


?>