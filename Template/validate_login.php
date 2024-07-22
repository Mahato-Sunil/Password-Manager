<?php
// Including the config file
require "../Configuration/config.php";

// Getting user values
$username_inpt = $_POST['username'];
$user_pwd_inpt = $_POST['password'];

// SQL query to select the data from the database
$query = "SELECT * FROM credentials";
$user_credentials = [];

// Checking the connection
try {
    // Creating connection
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error)
        throw new Exception("Connection Failed: " . $conn->connect_error);

    // Execute the query
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $user_credentials[] = [
                'orig_username' => $row['Username'],
                'orig_hash' => $row['Hash']
            ];
        }
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
} catch (Exception $e) {
    die($e->getMessage());
}

// Initialize variable to store found user data
$isValid = false;

// Location based on the user role
$loginNext = "../";

// Iterate over user credentials to find matching username and verify password
foreach ($user_credentials as $credential) {
    if ($username_inpt === $credential['orig_username'] && password_verify($user_pwd_inpt, $credential['orig_hash'])) {
        $isValid = true;
        echo "<script> window.location.href= '../Template/Homepage.php'; </script>";
        break;
    }
}

// Check if username and password are correct
if (!$isValid) {
    echo "<script>
             alert('Sorry! User Validation Failed');
             window.location.href='$loginNext';
          </script>";
    exit();
}
