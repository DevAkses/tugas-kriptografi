<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function decrypt_aes($ciphertext, $key) {
    $ciphertext = base64_decode($ciphertext);
    $iv_size = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($ciphertext, 0, $iv_size);
    $ciphertext = substr($ciphertext, $iv_size);
    return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['base64_image'])) {
        $base64_image = $_POST['base64_image'];
        $image_data = base64_decode($base64_image);
        if ($image_data === false) {
            die('Failed to decode Base64 image.');
        }
        
        $tmp_file = 'upload/temp_image.png';
        file_put_contents($tmp_file, $image_data);
        $image['tmp_name'] = $tmp_file;
        $image['error'] = UPLOAD_ERR_OK;
    } else {
        $image = $_FILES['image'];
    }

    if ($image['error'] != UPLOAD_ERR_OK) {
        die('Error during file upload: ' . $image['error']);
    }

    $image_info = getimagesize($image['tmp_name']);
    if ($image_info === false || $image_info[2] != IMAGETYPE_PNG) {
        die('Only PNG images are supported.');
    }

    $img = imagecreatefrompng($image['tmp_name']);
    if (!$img) {
        die('Failed to read the image.');
    }

    $width = imagesx($img);
    $height = imagesy($img);

    $binary_message = '';
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $b = $rgb & 0xFF;
            $binary_message .= $b & 1;
        }
    }

    $message = '';
    for ($i = 0; $i < strlen($binary_message); $i += 8) {
        $byte = substr($binary_message, $i, 8);
        $char = chr(bindec($byte));
        if ($char === "\0") break; 
        $message .= $char;
    }

    imagedestroy($img);

    if (isset($_POST['key'])) {
        $decrypted_message = decrypt_aes($message, $_POST['key']);
        echo "Pesan yang didekripsi: " . htmlspecialchars($decrypted_message);
    } else {
        echo "Kunci dekripsi tidak disertakan.";
    }
} else {
    echo "Invalid request.";
}
