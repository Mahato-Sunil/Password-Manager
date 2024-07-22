<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    ob_start();
    include 'retrieve_password.php';
    include 'EncryptionAlgorithm.php';

    $input = json_decode(file_get_contents('php://input'), true);

    $masterPassword = $input['masterPassword'];
    $hash = $input['hash'];

    $decryptedPassword = decryptPassword($hash, $masterPassword);

    if ($decryptedPassword !== false) {
        echo json_encode(['success' => true, 'decryptedPassword' => $decryptedPassword]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid master password or decryption failed.']);
    }
    ob_end_flush();
}

function decryptPassword($hash, $masterPassword)
{
    $decrypt = new EncryptionAlgorithm();
    $decryptedPwd = $decrypt->decryptPassword($hash, $masterPassword);
    return $decryptedPwd;
}
