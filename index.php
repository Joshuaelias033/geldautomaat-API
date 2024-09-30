<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");

include 'db.php';

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT *  FROM Accounts');
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}


?>