<?php
require "../Configuration/config.php";

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

    // Function to create or update the `password_vault` table
    function createOrUpdatePasswordVaultTable($pdo)
    {
        $password_vault_fields = [
            'id' => "int NOT NULL AUTO_INCREMENT PRIMARY KEY",
            'Website_Name' => "varchar(255) NOT NULL",
            'Website_Username' => "varchar(255) NOT NULL",
            'Website_Password_Hash' => "text NOT NULL",
            'Website_Notes' => "text NOT NULL"
        ];

        if (!tableExists($pdo, 'password_vault')) {
            $sql = "CREATE TABLE `password_vault` (
                `id` int NOT NULL AUTO_INCREMENT,
                `Website_Name` varchar(255) NOT NULL,
                `Website_Username` varchar(255) NOT NULL,
                `Website_Password_Hash` text NOT NULL,
                `Website_Notes` text NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            $pdo->exec($sql);
        } else {
            // Add/update columns if necessary
            foreach ($password_vault_fields as $field => $type) {
                if (!columnExists($pdo, 'password_vault', $field)) {
                    $sql = "ALTER TABLE `password_vault` ADD `$field` $type;";
                    $pdo->exec($sql);
                }
            }
        }
    }

    // Call the function to create or update the table
    createOrUpdatePasswordVaultTable($pdo);

    echo "<script> console.log('Database and table setup completed successfully.');</script>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
