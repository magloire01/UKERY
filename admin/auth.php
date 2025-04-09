<?php
require_once 'config.php';
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM admin WHERE nom = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user &&  $user['password']) {
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $user['id'];
            header('Location: index.php');
            exit();
        } else {
            header('Location: login.php?error=invalid_credentials');
            exit();
        }
    } catch(PDOException $e) {
        header('Location: login.php?error=db_error');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>