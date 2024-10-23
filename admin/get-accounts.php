<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve all accounts
    $stmt = $pdo->prepare('SELECT * FROM Accounts');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}

?>