<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data
    $data = json_decode(file_get_contents("php://input"), true);

    // Extract data from the input
    $accountId = $data['AccountId'];
    $amount = $data['Amount'];
    $transactionDate = $data['TransactionDate'];
    $transactionType = 'Deposit'; // Assuming this is a deposit

    // Insert the data into the database with pdo and prepared statements
    $sql = "INSERT INTO transactions (AccountId, Amount, TransactionDate, TransactionType) VALUES
    (:AccountId, :Amount, :TransactionDate, :TransactionType)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['AccountId' => $accountId, 'Amount' => $amount, 'TransactionDate' => $transactionDate, 'TransactionType' => $transactionType]);

    if (!$stmt){
        echo json_encode(['message' => 'Deposit failed']);
    }
    
    //now also update the account balance 
    $sql = "UPDATE accounts SET Balance = Balance + :Amount WHERE AccountId = :AccountId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['Amount' => $amount, 'AccountId' => $accountId]);

    echo json_encode(['message' => 'Deposit successful']);

}
?>
