<?php
require "../Configuration/config.php";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM password_vault";

$result = $conn->query($sql);

if ($result) {
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $userdata = [];
    foreach ($data as $row) {
        $userdata[] = [
            'Id' => $row['Id'],
            'Name' => $row['Website_Name'],
            'Hash' => $row['Website_Password_Hash'],
            'Note' => $row['Website_Notes'],
        ];
    }
} else {
    echo "Error: " . $conn->error;
}
// Close connection
$conn->close();
