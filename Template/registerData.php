<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn'])) {

    require "../Configuration/config.php";
    require "EncryptionAlgorithm.php";

    $w_name = htmlspecialchars($_POST['name']);
    $w_username  = htmlspecialchars($_POST['username']);
    $w_pwd = $_POST['password'];
    $w_note = htmlspecialchars(empty($_POST['notes'])) ? "User Hasn't Set Note" : htmlspecialchars($_POST['notes']);
    $w_master_pwd = htmlspecialchars($_POST['masterpwd']);

    // hashing the password 
    $hash = encryptPassword($w_pwd, $w_master_pwd);

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        $query = "INSERT INTO password_vault(Website_Name, Website_Username,  Website_Password_Hash, Website_Notes) VALUES (:NAME, :USERNAME, :PWD, :NOTES)";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':NAME', $w_name);
        $stmt->bindParam(':USERNAME', $w_username);
        $stmt->bindParam(':PWD', $hash);
        $stmt->bindParam(':NOTES', $w_note);

        $stmt->execute();
        $pdo->commit();

        header('Location: Homepage.php?success=1');
        exit();
    } catch (PDOException $e) {
        header('Location: Homepage.php?success=0    ');
        $pdo->rollBack();
        exit();
    }
}

// hash the password 
function encryptPassword($pwd, $masterPassword)
{
    $encrypt = new EncryptionAlgorithm();
    // Encrypt the password
    $encryptedPassword = $encrypt->encryptPassword($pwd, $masterPassword);
    return $encryptedPassword;
}
