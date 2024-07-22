<?php
class EncryptionAlgorithm
{
    private function generateKey($masterPassword, $salt)
    {
        return hash_pbkdf2('sha256', $masterPassword, $salt, 10000, 32, true);
    }

    public function encryptPassword($plainPassword, $masterPassword)
    {
        $salt = random_bytes(16); // Secure random salt
        $key = $this->generateKey($masterPassword, $salt);

        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($plainPassword, 'aes-256-cbc', $key, 0, $iv);

        return base64_encode($salt . $iv . $encrypted);
    }

    public function decryptPassword($encryptedData, $masterPassword)
    {
        $data = base64_decode($encryptedData);

        $salt = substr($data, 0, 16);
        $iv = substr($data, 16, openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = substr($data, 16 + openssl_cipher_iv_length('aes-256-cbc'));

        $key = $this->generateKey($masterPassword, $salt);

        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }
}
