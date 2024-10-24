<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    // Update the account with the given account id
    $stmt = $pdo->prepare('UPDATE accounts SET firstname = ?, lastname = ?, AccountNumber = ?, Balance = ? WHERE Accountid = ?');
    $stmt->execute([$input['FirstName'], $input['LastName'], $input['AccountNumber'], $input['Balance'], $input['AccountId']]);
    echo json_encode(['message' => 'Account updated']);

}

?>