<?php
session_start();
require_once 'dbconfig.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = $_POST['2fa_code'];
    $user_id = $_SESSION['pending_user_id'];
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $sql = "SELECT two_fa_code FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($entered_code == $row['two_fa_code']) {
            $_SESSION['user_id'] = $user_id;
            header("Location: dashboard.php");
            exit();
        }
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verifiser 2FA</title>
</head>
<body>
<form action="" method="POST">
    <label for="2fa_code">Skriv inn kode:</label>
    <input type="text" name="2fa_code" required>
    <button type="submit">Verifiser</button>
</form>
</body>
</html>
