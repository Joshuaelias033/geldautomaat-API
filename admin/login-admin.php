<?php
include '../db.php'; // Ensure this includes your database connection

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from JSON body
    $input = json_decode(file_get_contents("php://input"), true);
    $Email = $input['email'];
    $password = $input['password'];

    // Prepare a statement to look for an existing email
    $stmt = $pdo->prepare('SELECT * FROM Employees WHERE Email = ?');
    $stmt->execute([$Email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Verify the password
        if (password_verify($password, $result['PasswordHash'])) {
            //add loggedin boolean to the result
            $result['loggedin'] = true;
            $result['message'] = 'Logged in';
            echo json_encode($result);
        } else {
            echo json_encode(['loggedin' => false, 'message' => 'Invalid Password']);
        }
    } else {
        echo json_encode(['loggedin' => false, 'message' => 'Invalid Email']);
    }
}
?>