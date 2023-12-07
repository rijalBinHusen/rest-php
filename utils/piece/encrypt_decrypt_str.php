<?php

function encrypt_string($data, $key) {
  $iv_length = openssl_cipher_iv_length('AES-256-CBC');
  $iv = openssl_random_pseudo_bytes($iv_length);

  $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

  return base64_encode($iv . $encrypted_data);
}

function decrypt_string($encrypted_data, $key) {
  $data = base64_decode($encrypted_data);

  $iv_length = openssl_cipher_iv_length('AES-256-CBC');
  $iv = substr($data, 0, $iv_length);

  $decrypted_data = openssl_decrypt(substr($data, $iv_length), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

  return $decrypted_data;
}
