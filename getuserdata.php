<?php
include 'db.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //u will get the token, the message and the success status

    // Get the input data
    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $pdo->prepare('SELECT * FROM account_tokens WHERE Token = ?');
    $stmt->execute([$data['token']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    $stmt = $pdo->prepare('SELECT * FROM Accounts WHERE AccountId = ?');
    $stmt->execute([$result['AccountId']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($result);

}

?>