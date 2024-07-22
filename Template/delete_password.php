<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'retrieve_password.php';
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'];
    $deleted = deletePasswordById($id);
    if ($deleted) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete password.']);
    }
}
function deletePasswordById($id)
{
    require "../Configuration/config.php";
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM password_vault WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
    $conn->close();
}
