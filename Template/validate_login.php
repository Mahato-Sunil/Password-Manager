<?php
require "../Configuration/config.php";

// Getting user values from POST request
$username_inpt = $_POST['username'] ?? '';
$user_pwd_inpt = $_POST['password'] ?? '';

try {
    // Connect to MySQL server without specifying the database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");

    // Function to check if a table exists
    function tableExists($pdo, $table)
    {
        $result = $pdo->query("SHOW TABLES LIKE '$table'");
        return $result && $result->rowCount() > 0;
    }

    // Function to check if a column exists in a table
    function columnExists($pdo, $table, $column)
    {
        $result = $pdo->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        return $result && $result->rowCount() > 0;
    }

    // Function to create or update the `credentials` table
    function createOrUpdateCredentialsTable($pdo)
    {
        $credentials_fields = [
            'ID' => "int NOT NULL AUTO_INCREMENT PRIMARY KEY",
            'Username' => "varchar(255) NOT NULL",
            'Hash' => "varchar(255) NOT NULL"
        ];

        if (!tableExists($pdo, 'credentials')) {
            $sql = "CREATE TABLE `credentials` (
                `ID` int NOT NULL AUTO_INCREMENT,
                `Username` varchar(255) NOT NULL,
                `Hash` varchar(255) NOT NULL,
                PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            $pdo->exec($sql);

            // Insert default admin credentials
            $hash = password_hash('sunil9860', PASSWORD_BCRYPT);
            $pdo->exec("INSERT INTO `credentials` (`Username`, `Hash`) VALUES ('sunil', '$hash')");
        } else {
            // Add/update columns if necessary
            foreach ($credentials_fields as $field => $type) {
                if (!columnExists($pdo, 'credentials', $field)) {
                    $sql = "ALTER TABLE `credentials` ADD `$field` $type;";
                    $pdo->exec($sql);
                }
            }

            // Check if admin credentials already exist
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM `credentials` WHERE `Username` = 'sunil'");
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                // Insert default admin credentials
                $hash = password_hash('sunil9860', PASSWORD_BCRYPT);
                $pdo->exec("INSERT INTO `credentials` (`Username`, `Hash`) VALUES ('sunil', '$hash')");
            }
        }
    }

    // Call the function to create or update the `credentials` table
    createOrUpdateCredentialsTable($pdo);

    // Prepare and execute the query to fetch user credentials
    $stmt = $pdo->query("SELECT * FROM credentials");
    $user_credentials = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $user_credentials[] = [
            'orig_username' => $row['Username'],
            'orig_hash' => $row['Hash']
        ];
    }

    // Close the connection
    $pdo = null;
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Initialize variable to store found user data
$isValid = false;

// Debugging: Output input data and fetched credentials
// echo "Debug Info:<br>";
// echo "Input Username: " . htmlspecialchars($username_inpt) . "<br>";
// echo "Input Password: " . htmlspecialchars($user_pwd_inpt) . "<br>";
// echo "Fetched Credentials:<br>";

foreach ($user_credentials as $credential) {
    // echo "Username: " . htmlspecialchars($credential['orig_username']) . "<br>";
    // echo "Hash: " . htmlspecialchars($credential['orig_hash']) . "<br>";

    // Iterate over user credentials to find matching username and verify password
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
             window.location.href='../';
          </script>";
    exit();
}
