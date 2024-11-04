<?php
include '../db.php';

class AdminLogin {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $this->login($input);
        }
    }

    private function login($input) {
        $Email = $input['email'];
        $password = $input['password'];

        $stmt = $this->pdo->prepare('SELECT * FROM Employees WHERE Email = ?');
        $stmt->execute([$Email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if (password_verify($password, $result['PasswordHash'])) {
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
}

$adminLogin = new AdminLogin($pdo);
$adminLogin->handleRequest();
?>